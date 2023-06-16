<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Order_Service_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('basicOrderServiceGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('basic/order_service')->getCollection ();

        $collection->getSelect ()
            ->columns (
                "SUBSTRING_INDEX(shipping_method, '_', 1) AS shipping_method_1",
            )
        ;

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('basic')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('increment_id', array(
		    'header'  => Mage::helper ('basic')->__('Service #'),
		    'index'   => 'increment_id',
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('basic')->__('Customer'),
		    'index'   => 'customer_id',
            'type'    => 'options',
            'options' => self::getCustomers (),
		));
		$this->addColumn ('order_id', array(
		    'header' => Mage::helper ('basic')->__('Order ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'order_id',
		));
		$this->addColumn ('order_increment_id', array(
		    'header' => Mage::helper ('basic')->__('Order Inc. ID'),
		    'align'  => 'right',
		    'index'  => 'order_increment_id',
		));
		$this->addColumn ('shipping_method_1', array(
		    'header'  => Mage::helper ('basic')->__('Shipping Method'),
		    'index'   => 'shipping_method_1',
            'type'    => 'options',
            'options' => Mage::getModel ('basic/adminhtml_system_config_source_shipping_allmethods')->toArray (),
            'filter_index' => 'shipping_method',
            'filter_condition_callback' => array ($this, '_shippingmethodFilterConditionCallback'),
		));
		$this->addColumn ('payment_method', array(
		    'header'  => Mage::helper ('basic')->__('Payment Method'),
		    'index'   => 'payment_method',
            'type'    => 'options',
            'options' => Mage::getModel ('basic/adminhtml_system_config_source_payment_allmethods')->toArray (),
		));
		$this->addColumn ('subtotal_amount', array(
		    'header'  => Mage::helper ('basic')->__('Subtotal Amount'),
		    'index'   => 'subtotal_amount',
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'subtotal_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('shipping_amount', array(
		    'header'  => Mage::helper ('basic')->__('Shipping Amount'),
		    'index'   => 'shipping_amount',
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'shipping_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('total_amount', array(
		    'header'  => Mage::helper ('basic')->__('Total Amount'),
		    'index'   => 'total_amount',
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'total_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('state', array(
		    'header'  => Mage::helper ('basic')->__('State'),
		    'index'   => 'state',
            'type'    => 'options',
            'options' => Mage::getModel ('basic/adminhtml_system_config_source_order_service_state')->toArray (),
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('basic')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('basic')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
		));

        $this->addColumn ('action', array(
            'header'   => Mage::helper ('basic')->__('Order'),
            'width'    => '50px',
            'type'     => 'action',
            'getter'   => 'getOrderId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('basic')->__('View'),
                    'field'   => 'order_id',
                    'url'     => array(
                        'base'   => 'adminhtml/sales_order/view',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                ),
            ),
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('basic')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('basic')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}

    public static function getCustomers ()
    {
        $result = array ();

        $collection = Mage::getModel ('customer/customer')->getCollection ()
            ->addNameToSelect()
        ;

        foreach ($collection as $customer)
        {
            $result [$customer->getId ()] = sprintf ('%s - %s', $customer->getId (), $customer->getName ());
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
}

