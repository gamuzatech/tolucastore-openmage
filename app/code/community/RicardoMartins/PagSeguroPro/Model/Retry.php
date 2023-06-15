<?php

/**
 * Class RicardoMartins_PagSeguroPro_Model_Retry
 *
 * @author    Ricardo Martins <pagseguro-transparente@ricardomartins.net.br>
 */
class RicardoMartins_PagSeguroPro_Model_Retry extends RicardoMartins_PagSeguro_Model_Abstract
{
    const XML_PATH = 'payment/pagseguropro_retry/';
    const XML_PATH_IS_ACTIVE = 'payment/pagseguropro_retry/active';
    const XML_PATH_EMAIL_TEMPLATE = 'payment/pagseguropro_retry/email_template';
    const XML_PATH_EMAIL_IDENTITY = 'payment/pagseguropro_retry/email_identity';


    /**
     * Get the checkout code for specific $order
     * @return string | false
     * @param $order Mage_Sales_Model_Order
     */
    public function sendRetryPaymentRequest($order)
    {
        $rHelper = Mage::helper('ricardomartins_pagseguropro/retry');
        $helper = Mage::helper('ricardomartins_pagseguro');
        $canRetry = method_exists($helper, 'canRetryOrder') && $helper->canRetryOrder($order);
        if (!$canRetry) {
            return false;
        }
        $iHelper = Mage::helper('ricardomartins_pagseguropro/internal');

        $params = $iHelper->getPagseguroCheckoutParams($order, $order->getPayment());
        if ($rHelper->getConfigFlagValue('disable_boleto')) {
            $params['excludePaymentMethodGroup'] = 'BOLETO,DEPOSIT';
        }

        $checkoutReturn = $this->callApi($params, $order->getPayment(), 'checkout');

        if(false !== $checkoutReturn && isset($checkoutReturn->code)) {
            return (string)$checkoutReturn->code;
        }
        return $checkoutReturn;

    }

    public function cancelExpiredRetryOrders()
    {
        $rHelper = Mage::helper('ricardomartins_pagseguropro/retry');
        $daysToCancel = $rHelper->getConfigValue('days_to_cancel');

        if(!$rHelper->isRetryEnabled() || $daysToCancel == 0)
        {
            return;
        }
        $pHelper = Mage::helper('ricardomartins_pagseguro');

        $pHelper->writeLog('Iniciando cancelamento de pedidos expirados.');

        //Orders with pending payment, older than the configured time using PagSeguro CC
        $ordersToCancel = Mage::getModel('sales/order')->getCollection()
            ->join(
                array('payment' => 'sales/order_payment'),
                'main_table.entity_id = payment.parent_id',
                array('payment_method' => 'payment.method')
            )
            ->addFieldToFilter('status', Mage_Sales_Model_Order::STATE_HOLDED)
            ->addFieldToFilter('payment.method', 'rm_pagseguro_cc')
            ->addAttributeToFilter('created_at', array(
                'to' => Zend_Date::now()->subDay($daysToCancel)->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)
            ));

        $total = $ordersToCancel->count();
        $success = $fails = 0;
        /** @var Mage_Sales_Model_Order $order */
        foreach ($ordersToCancel as $order) {
            try{
                if($order->canUnhold()){
                    $order->unhold();
                }
//                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED);
                $order->cancel();
                $order->addStatusHistoryComment('Prazo para retentativa de pagamento expirado. Pedido cancelado automaticamente.', false);
                $order->save();
                $success++;
            }catch (Exception $e){
                $pHelper->writeLog('Falha ao cancelar pedido #' . $order->getIncrementId() . ': ' . $e->getMessage());
                $fails++;
            }
        }
        $pHelper->writeLog(sprintf(
            'Total de pedidos a cancelar: %d / Total cancelados: %s / Falhas: %d', $total, $success, $fails
        ));
    }
}