<?php

/**
 * Class RicardoMartins_PagSeguroPro_Model_Observer
 * Model of Retry Process
 *
 * @author    Ricardo Martins <pagseguro-transparente@ricardomartins.net.br>
 */
class RicardoMartins_PagSeguroPro_Model_Observer
{
    /**
     * @param $observer
     */
    public function beforeOrderCancellation(Varien_Event_Observer $observer)
    {
        /** @var RicardoMartins_PagSeguroPro_Helper_Retry $helper */
        $helper = Mage::helper('ricardomartins_pagseguropro/retry');

        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getOrderCancellation()->getOrder();

        $paymentMethod = $order->getPayment()->getMethod();
        if (!$helper->isRetryEnabled() || $paymentMethod != 'rm_pagseguro_cc') {
            return;
        }

        if ($helper->getConfigFlagValue('send_email')) {
            $helper->sendRetryEmail($observer->getOrderCancellation());
        }

        $observer->getOrderCancellation()->setData('should_cancel', false);
    }
}