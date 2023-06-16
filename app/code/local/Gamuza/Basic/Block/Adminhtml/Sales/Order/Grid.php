<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml sales orders grid
 */
class Gamuza_Basic_Block_Adminhtml_Sales_Order_Grid
    extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected $_countTotals = true;

    protected $_isExport = true;

    public $_fieldsTotals = array(
        'base_subtotal' => 0,
        'base_shipping_amount' => 0,
        'base_grand_total' => 0,
        'base_customer_balance_amount' => 0
    );

    protected function _prepareCollection ()
    {
        parent::_prepareCollection ();

        $this->getCollection ()->getSelect ()->columns ("SUBSTRING_INDEX(shipping_method, '_', 1) AS shipping_method_1");

        $this->getCollection ()->getSelect ()->joinLeft (
            array ('payment' => Mage::getSingleton ('core/resource')->getTableName ('sales/order_payment')),
            'main_table.entity_id = payment.parent_id',
            array ('payment_method' => 'payment.method', 'base_shipping_amount')
        );

        return $this;
    }

    protected function _prepareColumns ()
    {
        parent::_prepareColumns ();

        $this->getColumn ('real_order_id')->setData ('totals_label', $this->__('Total'));

        $this->getColumn ('base_grand_total')
            // ->setHeader ('G.T. (Base)')
            ->setData ('renderer', 'basic/adminhtml_widget_grid_column_renderer_total')
        ;

        $this->getColumn ('grand_total')
            // ->setHeader ('G.T. (Comprado)')
            ->setData ('renderer', 'basic/adminhtml_widget_grid_column_renderer_total')
        ;

        $this->removeColumn ('grand_total');

        $store = $this->_getStore();

        $this->addColumnAfter ('base_subtotal', array(
            'header'   => Mage::helper ('sales')->__('Subtotal'),
            'index'    => 'base_subtotal',
            'type'     => 'price',
            'currency_code' => $store->getBaseCurrency ()->getCode (),
        ), 'shipping_name');

        $this->addColumnAfter ('base_shipping_amount', array(
            'header'   => Mage::helper ('sales')->__('Shipping Amount'),
            'index'    => 'base_shipping_amount',
            'type'     => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'filter_index' => 'payment.base_shipping_amount',
            'filter_condition_callback' => array ($this, '_shippingamountFilterConditionCallback'),
        ), 'base_subtotal');
/*
        $this->addColumnAfter ('base_customer_balance_amount', array(
            'header'   => Mage::helper ('sales')->__('Store Credit'),
            'index'    => 'base_customer_balance_amount',
            'type'     => 'price',
            'currency_code' => $store->getBaseCurrency ()->getCode (),
        ), 'base_shipping_amount');
*/
        $this->addColumnAfter ('shipping_method_1', array(
            'header'   => Mage::helper ('sales')->__('Shipping'),
            'index'    => 'shipping_method_1',
            'type'     => 'options',
            'options'  => $this->_getShippingMethods (),
            'filter_index' => 'shipping_method',
            'filter_condition_callback' => array ($this, '_shippingmethodFilterConditionCallback'),
        ), 'base_grand_total');

        $this->addColumnAfter ('payment_method', array(
            'header'   => Mage::helper ('sales')->__('Payment'),
            'index'    => 'payment_method',
            'type'     => 'options',
            'options'  => $this->_getPaymentMethods (),
            'filter_index' => 'payment.method',
            'filter_condition_callback' => array ($this, '_paymentmethodFilterConditionCallback'),
        ), 'shipping_method_1');

        $this->addColumnAfter('color_state', array(
            'header'   => Mage::helper('sales')->__('Color'),
            'index'    => 'state',
            'type'     => 'options',
            'width'    => '70px',
            'options'  => Mage::getSingleton('sales/order_config')->getStates(),
            'renderer' => 'basic/adminhtml_widget_grid_column_renderer_color',
        ), 'status');

        $this->sortColumnsByOrder ();
    }

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        $this->getMassactionBlock()->removeItem('hold_order');
        $this->getMassactionBlock()->removeItem('unhold_order');
        $this->getMassactionBlock()->removeItem('cancel_order');
    }

    public function getTotals ()
    {
        return $this->helper ('basic')->getTotals ($this);
    }

    private function _getShippingMethods ()
    {
        $result = array ();

        foreach (Mage::getSingleton ('shipping/config')->getAllCarriers () as $carrier)
        {
            $id = explode ('_', $carrier->getId ());

            $result [$id [0]] = $carrier->getConfigData ('title');
        }

        return $result;
    }

    private function _getPaymentMethods ()
    {
        $result = array ();

        foreach (Mage::getSingleton ('payment/config')->getAllMethods () as $carrier)
        {
            $result [$carrier->getId ()] = $carrier->getConfigData ('title');
        }

        return $result;
    }

    protected function _shippingamountFilterConditionCallback ($collection, $column)
    {
        $value = $column->getFilter ()->getValue ();

        if (!empty ($value))
        {
            if (isset ($value ['from']))
            {
                $this->getCollection ()->getSelect ()->where (sprintf ("%s >= %s", $column->getFilterIndex (), $value ['from']));
            }

            if (isset ($value ['to']))
            {
                $this->getCollection ()->getSelect ()->where (sprintf ("%s <= %s", $column->getFilterIndex (), $value ['to']));
            }
        }

        return $this;
    }

    protected function _shippingmethodFilterConditionCallback ($collection, $column)
    {
        $value = $column->getFilter ()->getValue ();

        if (!empty ($value))
        {
            $this->getCollection ()->getSelect ()->where (sprintf ("%s LIKE '%s_%%'", $column->getFilterIndex (), $value));
        }

        return $this;
    }

    protected function _paymentmethodFilterConditionCallback ($collection, $column)
    {
        $value = $column->getFilter ()->getValue ();

        if (!empty ($value))
        {
            $this->getCollection ()->getSelect ()->where (sprintf ("%s = '%s'", $column->getFilterIndex (), $value));
        }

        return $this;
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

    public function addRssList ($url, $label)
    {
        // nothing here
    }
}

