<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_countTotals = true;

    protected $_isExport = true;

    public $_fieldsTotals = array(
        'total_amount' => 0,
        'subtotal_amount' => 0,
        'shipping_amount' => 0,
    );

	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('pdvLogGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('pdv/log')->getCollection ();

        $collection->getSelect ()
            ->columns (
                "SUBSTRING_INDEX(shipping_method, '_', 1) AS shipping_method_1"
            )
        ;

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

    public function getTotals ()
    {
        return $this->helper ('pdv')->getTotals ($this);
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
		    'header' => Mage::helper ('pdv')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('cashier_id', array(
		    'header'  => Mage::helper ('pdv')->__('Cashier'),
		    'index'   => 'cashier_id',
            'type'    => 'options',
            'options' => Toluca_PDV_Block_Adminhtml_Operator_Grid::getCashiers (),
		));
		$this->addColumn ('operator_id', array(
		    'header'  => Mage::helper ('pdv')->__('Operator'),
		    'index'   => 'operator_id',
            'type'    => 'options',
            'options' => Toluca_PDV_Block_Adminhtml_Cashier_Grid::getOperators (),
		));
		$this->addColumn ('history_id', array(
		    'header'  => Mage::helper ('pdv')->__('History ID'),
		    'index'   => 'history_id',
            'type'    => 'number',
		));
		$this->addColumn ('sequence_id', array(
		    'header'  => Mage::helper ('pdv')->__('Sequence ID'),
		    'index'   => 'sequence_id',
            'type'    => 'number',
		));
		$this->addColumn ('type_id', array(
		    'header'  => Mage::helper ('pdv')->__('Type'),
		    'index'   => 'type_id',
            'type'    => 'options',
            'options' => Mage::getModel ('pdv/adminhtml_system_config_source_log_type')->toArray (),
		));
		$this->addColumn ('total_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Total Amount'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'total_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('pdv')->__('Message'),
		    'index'   => 'message',
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('pdv')->__('Customer'),
		    'index'   => 'customer_id',
            'type'    => 'options',
            'options' => self::getCustomers (),
		));
/*
		$this->addColumn ('quote_id', array(
		    'header'  => Mage::helper ('pdv')->__('Quote ID'),
		    'index'   => 'quote_id',
            'type'    => 'number',
		));
*/
		$this->addColumn ('order_increment_id', array(
		    'header'  => Mage::helper ('pdv')->__('Order Inc. ID'),
		    'index'   => 'order_increment_id',
		));
		$this->addColumn ('payment_method', array(
		    'header'  => Mage::helper ('pdv')->__('Payment Method'),
		    'index'   => 'payment_method',
            'type'    => 'options',
            'options' => Mage::getModel ('pdv/adminhtml_system_config_source_payment_allmethods')->toArray (),
		));
		$this->addColumn ('subtotal_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Subtotal Amount'),
		    'index'   => 'subtotal_amount',
		    'align'   => 'right',
	        'type'    => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('shipping_method_1', array(
		    'header'  => Mage::helper ('pdv')->__('Shipping Method'),
		    'index'   => 'shipping_method_1',
            'type'    => 'options',
            'options' => Mage::getModel ('pdv/adminhtml_system_config_source_shipping_allmethods')->toArray (),
            'filter_index' => 'shipping_method',
            'filter_condition_callback' => array ($this, '_shippingmethodFilterConditionCallback'),
		));
		$this->addColumn ('shipping_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Shipping Amount'),
		    'index'   => 'shipping_amount',
		    'align'   => 'right',
	        'type'    => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('pdv')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
		));
/*
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('pdv')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
		));
*/
        $this->addExportType('*/*/exportCsv', Mage::helper('pdv')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('pdv')->__('Excel XML'));

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

