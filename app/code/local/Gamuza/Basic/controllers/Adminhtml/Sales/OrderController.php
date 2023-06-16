<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'OrderController.php');

/**
 * Adminhtml sales orders controller
 */
class Gamuza_Basic_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = array ('pending');

    public function pendingAction()
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('state', array ('eq' => Mage_Sales_Model_Order::STATE_NEW))
        ;

        $collection->getSelect ()->reset (Zend_Db_Select::COLUMNS)
            ->columns (array ('qty' => 'COUNT(main_table.entity_id)'))
        ;

        $this->getResponse ()->setBody ($collection->getFirstItem ()->getQty ());
    }

    /**
     * Cancel order
     */
    public function cancelAction()
    {
        parent::cancelAction();

        if ($order = Mage::registry ('current_order'))
        {
            Mage::helper ('basic/sales_order_status')->canceled ($order);
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $order->getId ()));
    }

    /**
     * Prepare order to delivery
     */
    public function prepareAction()
    {
        if ($order = $this->_initOrder ())
        {
            try
            {
                Mage::helper ('basic/sales_order_status')->preparing ($order);

                $this->_getSession()->addSuccess ($this->__('The order notification has been sent.'));
            }
            catch (Mage_Core_Exception $e)
            {
                $this->_getSession ()->addError ($e->getMessage ());
            }
            catch (Exception $e)
            {
                $this->_getSession ()->addError ($this->__('Failed to send the order notification.'));

                // Mage::logException ($e);
            }
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $order->getId ()));
    }

    /**
     * Delivered order status
     */
    public function deliveredStatusAction()
    {
        if ($order = $this->_initOrder ())
        {
            try
            {
                Mage::helper ('basic/sales_order_status')->delivered ($order);

                $this->_getSession()->addSuccess ($this->__('The order notification has been sent.'));
            }
            catch (Mage_Core_Exception $e)
            {
                $this->_getSession ()->addError ($e->getMessage ());
            }
            catch (Exception $e)
            {
                $this->_getSession ()->addError ($this->__('Failed to send the order notification.'));

                // Mage::logException ($e);
            }
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $order->getId ()));
    }

    /**
     * Print order status
     */
    public function printAction()
    {
        if ($order = $this->_initOrder ())
        {
            try
            {
                $order->setData('is_printed', 0)->save ();

                $this->_getSession()->addSuccess ($this->__('The order notification has been sent.'));
            }
            catch (Mage_Core_Exception $e)
            {
                $this->_getSession ()->addError ($e->getMessage ());
            }
            catch (Exception $e)
            {
                $this->_getSession ()->addError ($this->__('Failed to send the order notification.'));

                // Mage::logException ($e);
            }
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $order->getId ()));
    }
}

