<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Order_Payment_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
	protected $_countTotals = true;

	protected $_isExport = true;

	public $_fieldsTotals = array(
		'base_grand_total' => 0,
		'total' => 0,
		'amount' => 0,
		'cash_amount' => 0,
		'change_amount' => 0
	);

	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('basicOrderPaymentGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('basic/order_payment')->getCollection ();

		$collection->getSelect ()
            ->joinLeft (
                array ('sfo' => Mage::getSingleton ('core/resource')->getTableName ('sales/order')),
                'main_table.order_id = sfo.entity_id',
                array ('increment_id')
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
			'filter_index' => 'main_table.entity_id',
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('basic')->__('Customer'),
		    'index'   => 'customer_id',
            'type'    => 'options',
            'options' => self::getCustomers (),
			'filter_index' => 'main_table.customer_id',
		));
		$this->addColumn ('order_id', array(
		    'header' => Mage::helper ('basic')->__('Order ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'order_id',
		));
		$this->addColumn ('increment_id', array(
		    'header' => Mage::helper ('basic')->__('Order Inc. ID'),
		    'index'  => 'increment_id',
		));
		$this->addColumn ('quote_id', array(
		    'header'  => Mage::helper ('basic')->__('Quote ID'),
			'align'  => 'right',
	        'type'   => 'number',
		    'index'   => 'quote_id',
			'filter_index' => 'main_table.quote_id',
		));
		$this->addColumn ('payment_id', array(
		    'header'  => Mage::helper ('basic')->__('Payment ID'),
            'align'   => 'right',
            'type'   => 'number',
		    'index'   => 'payment_id',
		));
		$this->addColumn ('method', array(
		    'header'  => Mage::helper ('basic')->__('Payment Method'),
		    'index'   => 'method',
            'type'    => 'options',
            'options' => Mage::getModel ('basic/adminhtml_system_config_source_payment_allmethods')->toArray (),
		));
		$this->addColumn ('total', array(
		    'header'  => Mage::helper ('basic')->__('Total'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'total',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('amount', array(
		    'header'  => Mage::helper ('basic')->__('Amount'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('customer_name', array(
		    'header'  => Mage::helper ('basic')->__('Customer Name'),
		    'index'   => 'customer_name',
		));
		$this->addColumn ('cash_amount', array(
		    'header'  => Mage::helper ('basic')->__('Cash Amount'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'cash_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('change_amount', array(
		    'header'  => Mage::helper ('basic')->__('Change Amount'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'change_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
		));
		$this->addColumn ('change_type', array(
		    'header'  => Mage::helper ('basic')->__('Need Change'),
			'index'   => 'change_type',
	        'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('cc_type', array(
		    'header'  => Mage::helper ('basic')->__('Card Type'),
		    'index'   => 'cc_type',
		));
		$this->addColumn ('po_number', array(
		    'header'  => Mage::helper ('basic')->__('PO Number'),
		    'index'   => 'po_number',
		));
		$this->addColumn ('is_default', array(
		    'header'  => Mage::helper ('basic')->__('Is Default'),
		    'index'   => 'is_default',
			'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('basic')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
			'filter_index' => 'main_table.created_at',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('basic')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
			'filter_index' => 'main_table.updated_at',
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

	public function getTotals ()
    {
        return $this->helper ('basic')->getTotals ($this);
    }
}

