<?php
/**
 * PagSeguro Transparente Magento
 * Model CC Class - responsible for credit card payment processing
 *
 * @category    RicardoMartins
 * @package     RicardoMartins_PagSeguro
 * @author      Ricardo Martins
 * @copyright   Copyright (c) 2015 Ricardo Martins (http://r-martins.github.io/PagSeguro-Magento-Transparente/)
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class RicardoMartins_PagSeguroPro_Model_Payment_Redirect extends RicardoMartins_PagSeguro_Model_Abstract
{
    const XML_PATH_REDIRECTURL = 'payment/pagseguropro_redirect/redirectURL';

    protected $_code = 'pagseguropro_redirect';
    protected $_formBlockType = 'ricardomartins_pagseguropro/form_redirect';
    protected $_infoBlockType = 'ricardomartins_pagseguropro/form_info_redirect';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid = true;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = false;
    protected $_canSaveCc = false;



    /**
     * Check if module is available for current quote and customer group (if restriction is activated)
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        $isAvailable = parent::isAvailable($quote);
        if (empty($quote)) {
            return $isAvailable;
        }

        if($this->getConfigData('disable_frontend') && !Mage::app()->getStore()->isAdmin()) {
            return false;
        }

        return $isAvailable;
    }


    /**
     * Generically get module's config field value
     * @param $field
     *
     * @return mixed
     */
    public function getStoreConfig($field)
    {
        return Mage::getStoreConfig("payment/pagseguropro_redirect/{$field}");
    }


    public function order(Varien_Object $payment, $amount)
    {
        /** @var RicardoMartins_PagSeguroPro_Helper_Internal $pHelper */
        $helper = Mage::helper('ricardomartins_pagseguropro/internal');

        /** @var RicardoMartins_PagSeguro_Helper_Data $rmHelper */
        $rmHelper = Mage::helper('ricardomartins_pagseguro');

        /** @var Mage_Sales_Model_Order $order */
        $order = $payment->getOrder();

        $params = explode('&', 'currency=BRL&itemId1=0001&itemDescription1=Notebook%20Prata&itemAmount1=24300.00&itemQuantity1=1&itemWeight1=1000&reference=REF1234&senderName=Jose%20Comprador&senderEmail=comprador%40uol.com.br&shippingType=1&shippingAddressStreet=Av.%20Brig.%20Faria%20Lima&shippingAddressNumber=1384&shippingAddressComplement=5o%20andar&shippingAddressDistrict=Jardim%20Paulistano&shippingAddressPostalCode=01452002&shippingAddressCity=Sao%20Paulo&shippingAddressState=SP&shippingAddressCountry=BRA&acceptPaymentMethodGroup=CREDIT_CARD%2CBOLETO&acceptPaymentMethodName=DEBITO_ITAU%2CDEBITO_BRADESCO&public_key=PUB9CBFB26210F947409D50414439DFEC09&undefined=');
        $params = $helper->getRedirectParams($order, $payment);

        $returnXml = $this->callApi($params, $payment, 'checkout');
        $returnXml = $helper->validate($returnXml);

        if (isset($returnXml->errors)) {
            $errMsg = array();
            foreach ($returnXml->errors as $error) {
                $errMsg[] = $rmHelper->__((string)$error->message) . ' (' . $error->code . ')';
            }
            Mage::throwException('Um ou mais erros ocorreram no seu pagamento.' . PHP_EOL . implode(PHP_EOL, $errMsg));
        }
        if (isset($returnXml->error->message)) {
            $message = (string)$returnXml->error->message;
            $msg = 'Um erro ocorreu: ' . $rmHelper->__($message);

            Mage::throwException($msg);
        }
        if (isset($returnXml->error)) {
            $error = $returnXml->error;
            $errMsg[] = $rmHelper->__((string)$error->message) . ' (' . $error->code . ')';
            Mage::throwException('Um erro ocorreu em seu pagamento.' . PHP_EOL . implode(PHP_EOL, $errMsg));
        }

        $payment->setSkipOrderProcessing(true);

        if (isset($returnXml->code)) {
            $code = (string)$returnXml->code;
            $redirUrl = 'https://pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $code;
            if (method_exists($rmHelper, 'isSandbox') && $rmHelper->isSandbox()) {
                $redirUrl = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $code;
            }
            $payment->setAdditionalInformation(array('redirect_url' => $redirUrl));
            $order->sendNewOrderEmail();
            $this->setRedirectUrl($redirUrl);
        }

        return $this;
    }

    public function getOrderPlaceRedirectUrl()
    {
        $reservedId = $this->getInfoInstance()->getQuote()->getReservedOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($reservedId);
        $url = $order->getPayment()->getAdditionalInformation('redirect_url');

        return $url;
    }

}
