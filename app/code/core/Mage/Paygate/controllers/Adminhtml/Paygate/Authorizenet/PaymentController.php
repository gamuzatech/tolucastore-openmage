<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Paygate
 */

/**
 * Authorize Payment Controller
 *
 * @package    Mage_Paygate
 */
class Mage_Paygate_Adminhtml_Paygate_Authorizenet_PaymentController extends Mage_Adminhtml_Controller_Action
{
    /**
     * ACL resource
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     */
    public const ADMIN_RESOURCE = 'sales/order/actions/review_payment';

    /**
     * Cancel active partail authorizations
     */
    public function cancelAction()
    {
        $result['success'] = false;
        try {
            $paymentMethod = Mage::helper('payment')->getMethodInstance(Mage_Paygate_Model_Authorizenet::METHOD_CODE);
            if ($paymentMethod) {
                $paymentMethod->setStore(Mage::getSingleton('adminhtml/session_quote')->getQuote()->getStoreId());
                $paymentMethod->cancelPartialAuthorization(Mage::getSingleton('adminhtml/session_quote')->getQuote()->getPayment());
            }

            $result['success']  = true;
            $result['update_html'] = $this->_getPaymentMethodsHtml();
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            $result['error_message'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error_message'] = $this->__('There was an error canceling transactions. Please contact us or try again later.');
        }

        Mage::getSingleton('adminhtml/session_quote')->getQuote()->getPayment()->save();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Get payment method step html
     *
     * @return string
     */
    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
}
