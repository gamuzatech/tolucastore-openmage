<?php

/**
 * Class RicardoMartins_PagSeguroPro_Helper_Retry
 * Helper for Retry Process
 *
 * @author    Ricardo Martins <pagseguro-transparente@ricardomartins.net.br>
 */
class RicardoMartins_PagSeguroPro_Helper_Retry extends Mage_Core_Helper_Abstract
{

    /**
     * Checks if order retry is enabled
     * @return bool
     */
    public function isRetryEnabled()
    {
        $isEnabled =  Mage::getStoreConfigFlag(RicardoMartins_PagSeguroPro_Model_Retry::XML_PATH_IS_ACTIVE);
        if (!$isEnabled) {
            return false;
        }
        $moduleConfig = Mage::getConfig()->getModuleConfig('RicardoMartins_PagSeguro');

        if (version_compare($moduleConfig->version, '3.7', '<')) {
            Mage::helper('ricardomartins_pagseguro')
                ->writeLog('Para usar a retentativa você precisa da versão 3.7 ou superior do módulo principal.');
            return false;
        }
        return true;
    }

    /**
     * Get some config value from this module in the specified node
     * @param $configNode
     *
     * @return string
     */
    public function getConfigValue($configNode)
    {
        return Mage::getStoreConfig(RicardoMartins_PagSeguroPro_Model_Retry::XML_PATH . $configNode);
    }

    /**
     * Get some config  flag value from this module in the specified node
     * @param $configNode
     *
     * @return boolean
     */
    public function getConfigFlagValue($configNode)
    {
        return Mage::getStoreConfigFlag(RicardoMartins_PagSeguroPro_Model_Retry::XML_PATH . $configNode);
    }

    /**
     * Return the template name of the selected layout
     * @deprecated not in use
     * @return mixed
     * @throws Varien_Exception
     */
    public function getLayoutConfig()
    {
        $layout = $this->getConfigValue('layout');
        return Mage::getModel('page/config')->getPageLayout($layout)->getTemplate();
    }


    /**
     * Sends the retry e-mail
     * @param Varien_Object $orderCancellation
     */
    public function sendRetryEmail($orderCancellation)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $orderCancellation->getOrder();
        /** @var Mage_Core_Model_Email_Template_Mailer $mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($order->getCustomerEmail(), $order->getCustomerName());
        $mailer->addEmailInfo($emailInfo);
        $retryLink = Mage::getUrl('pseguropro/retry', array(
           '_secure' => true,
           '_use_rewrite' => false,
           '_query' => array(
               'pid' => Mage::helper('core')->encrypt($order->getPayment()->getId())
           ),
        ));

        //Set all required params and send email
        $mailer->setSender(Mage::getStoreConfig(RicardoMartins_PagSeguroPro_Model_Retry::XML_PATH_EMAIL_IDENTITY));
        $mailer->setStoreId($order->getStoreId());
        $mailer->setTemplateId(Mage::getStoreConfig(RicardoMartins_PagSeguroPro_Model_Retry::XML_PATH_EMAIL_TEMPLATE));
        $mailer->setTemplateParams(array(
            'order' => $order,
            'retry_link' => $retryLink,
            'days_to_cancel' => $this->getConfigValue('days_to_cancel'),
            'customer' => $order->getCustomerName(),
            'external' => $orderCancellation->getCancellationSource() == 'EXTERNAL',
            'payment_html' => $this->getPaymentHtml($order)
        ));
        $mailer->send();
    }

    /**
     * Retrieves a payment link from pagseguro for the specified $order. Customer can chose the payment method later.
     * @param $order
     *
     * @return string|false PagSeguro checkout URL for this specified order or false if fails
     */
    public function getPagseguroPaymentLink($order)
    {
        $code = Mage::getModel('ricardomartins_pagseguropro/retry')->sendRetryPaymentRequest($order);

        if (false !== $code) {
            $sandbox = strpos($order->getCustomerEmail(), '@sandbox.pagseguro') !== false;
            $sandbox = ($sandbox) ? 'sandbox.' : '';
            return "https://{$sandbox}pagseguro.uol.com.br/v2/checkout/payment.html?code={$code}";
        }

        return false;
    }

    /**
     * @param $order Mage_Sales_Model_Order
     * @deprecated not in use
     * @return string
     * @throws Varien_Exception
     */
    protected function getPaymentHtml($order)
    {
        $storeId = $order->getStoreId();
        $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment())
            ->setIsSecureMode(true);
        $paymentBlock->getMethod()->setStore($storeId);
        return $paymentBlock->toHtml();
    }
}