<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml product orders grid block
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tab_Orders
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('product_orders_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
    }

    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('base_grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            ->setIsCustomerMode(true)
        ;

        $collection->getSelect()
            ->join(
                array('sfoi' => Mage::getSingleton('core/resource')->getTablename('sales/order_item')),
                'main_table.entity_id = sfoi.order_id',
                array()
            )
            ->where ('sfoi.product_id = ?', Mage::registry('current_product')->getId())
        ;

        $collection->getSelect()
            ->joinLeft (
                array ('payment' => Mage::getSingleton ('core/resource')->getTableName ('sales/order_payment')),
                'main_table.entity_id = payment.parent_id',
                array ('payment_method' => 'payment.method', 'base_shipping_amount')
            )
            ->columns ("SUBSTRING_INDEX(shipping_method, '_', 1) AS shipping_method_1")
        ;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('customer')->__('Order #'),
            'width'     => '100',
            'index'     => 'increment_id',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('customer')->__('Purchase On'),
            'index'     => 'created_at',
            'type'      => 'datetime',
            'filter_index' => 'main_table.created_at',
        ));

        $this->addColumn('billing_name', array(
            'header'    => Mage::helper('customer')->__('Bill to Name'),
            'index'     => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header'    => Mage::helper('customer')->__('Shipped to Name'),
            'index'     => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header'    => Mage::helper('customer')->__('Order Total'),
            'index'     => 'base_grand_total',
            'type'      => 'currency',
            'currency'  => 'order_currency_code',
        ));

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

        if (!Mage::app()->isSingleStoreMode())
        {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('customer')->__('Bought From'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view' => true
            ));
        }

        if (Mage::helper('sales/reorder')->isAllow())
        {
            $this->addColumn('action', array(
                'header'    => ' ',
                'filter'    => false,
                'sortable'  => false,
                'width'     => '100px',
                'renderer'  => 'adminhtml/sales_reorder_renderer_action'
            ));
        }

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/orders', array('_current' => true));
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
}

