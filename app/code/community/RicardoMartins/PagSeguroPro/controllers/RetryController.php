<?php
/**
 * Class RicardoMartins_PagSeguroPro_RetryController
 *
 * @author    Ricardo Martins <pagseguro-transparente@ricardomartins.net.br>
 */
class RicardoMartins_PagSeguroPro_RetryController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $paymentId = $this->getRequest()->getParam('pid', false);
        if (!$paymentId) {
            return $this->_throwError('Código de pagamento inválido.');
        }

        $paymentId = (int)Mage::helper('core')->decrypt($paymentId);

        /** @var Mage_Sales_Model_Order_Payment $paymentDetails */
        $paymentDetails = Mage::getModel('sales/order_payment')->load($paymentId);
        if (!$paymentDetails->getId()) {
            return $this->_throwError('Código de pagamento não encontrado.');
        }

        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->load($paymentDetails->getParentId());
        if (!$order->getId()) {
            return $this->_throwError('Não foi possível carregar o pedido desta transação. Por favor entre em contato conosco.');
        }

        $helper = Mage::helper('ricardomartins_pagseguropro/retry');
        if (!$helper->isRetryEnabled()) {
            return $this->_throwError('Recuperação de pagamentos não está disponível.');
        }

        /** @var Zend_Date $orderDate */
        $orderDate = $order->getCreatedAtDate();
        $daysToCancel = $helper->getConfigValue('days_to_cancel');
        $dateToCancel = Zend_Date::now()->subDay($daysToCancel);
        if ($orderDate->isEarlier($dateToCancel)) {
            return $this->_throwError('O prazo para retentativa para pagamento expirou. Por favor refaça seu pedido.');
        }

        if ($order->getTotalDue() <= 0) {
            return $this->_throwError('Não há mais valor devido para este pedido. O pedido parece já ter sido pago.');
        }

        $link = $helper->getPagseguroPaymentLink($order);

        if (!$link) {
            return $this->_throwError('Houve uma falha ao gerar a retentativa de pagamento para seu pedido. Se o problema persistir, entre em contato conosco.');
        }
        $order->addStatusHistoryComment(
            'O cliente iniciou uma re-tentativa de pagamento acessando o link enviado.', false
        )->save();
        $this->_redirectUrl($link);
    }

    /**
     * Add exception msg to core sesison and redirect to home page
     * @param $errorMsg
     */
    protected function _throwError($errorMsg)
    {
        Mage::getSingleton('customer/session')->addError($errorMsg);
        $this->_redirect('/');
    }
}