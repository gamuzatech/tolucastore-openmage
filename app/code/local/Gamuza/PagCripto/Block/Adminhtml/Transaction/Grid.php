<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('pagcriptoTransactionGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('pagcripto/transaction')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('pagcripto')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('store_id', array(
		    'header'  => Mage::helper ('pagcripto')->__('Store'),
		    'align'   => 'right',
		    'index'   => 'store_id',
	        'type'    => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getStoreOptionHash (true),
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('pagcripto')->__('Customer'),
		    'align'   => 'right',
		    'index'   => 'customer_id',
	        'type'    => 'options',
		    'options' => self::_getCustomers (),
		));
/*
		$this->addColumn ('order_id', array(
		    'header'  => Mage::helper ('pagcripto')->__('Order ID'),
		    'align'   => 'right',
		    'index'   => 'order_id',
		));
*/
		$this->addColumn ('order_increment_id', array(
		    'header'  => Mage::helper ('pagcripto')->__('Order Inc. ID'),
		    'align'   => 'right',
		    'index'   => 'order_increment_id',
		));
		$this->addColumn ('currency', array(
		    'header'  => Mage::helper ('pagcripto')->__('Currency'),
		    'index'   => 'currency',
            'type'    => 'options',
            'options' => Mage::getModel ('pagcripto/adminhtml_system_config_source_payment_cctype')->toArray (),
		));
		$this->addColumn ('address', array(
		    'header'  => Mage::helper ('pagcripto')->__('Address'),
		    'align'   => 'right',
		    'index'   => 'address',
		));
        $this->addColumn('amount', array(
            'header'  => Mage::helper ('pagcripto')->__('Amount'),
            'align'   => 'right',
            'index'   => 'amount',
        ));
        $this->addColumn('payment_request', array(
            'header'  => Mage::helper ('pagcripto')->__('Payment Request'),
            'align'   => 'right',
            'index'   => 'payment_request',
        ));
		$this->addColumn ('received_amount', array(
		    'header'  => Mage::helper ('pagcripto')->__('Received Amount'),
            'align'   => 'right',
		    'index'   => 'received_amount',
		));
		$this->addColumn ('txid', array(
		    'header'  => Mage::helper ('pagcripto')->__('TxID'),
            'align'   => 'right',
		    'index'   => 'txid',
		));
		$this->addColumn ('confirmations', array(
		    'header'  => Mage::helper ('pagcripto')->__('Confirmations'),
            'align'   => 'right',
		    'index'   => 'confirmations',
		));
/*
		$this->addColumn ('description', array(
		    'header'  => Mage::helper ('pagcripto')->__('Description'),
		    'index'   => 'description',
		));
*/
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('pagcripto')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('pagcripto/adminhtml_system_config_source_payment_status')->toArray (),
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('pagcripto')->__('Message'),
		    'index'   => 'message',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('pagcripto')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('pagcripto')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing
	}

    public function _getCustomers ($websiteId = null)
    {
        $collection = Mage::getModel ('customer/customer')->getCollection ();

        if (!empty ($websiteId))
        {
            $collection->addFieldToFilter ('website_id', $websiteId);
        }

        $collection->getSelect ()->reset (Zend_Db_Select::COLUMNS)
            ->columns (array ('id' => 'e.entity_id', 'name' => 'e.email'))
        ;

        return $collection->toOptionHash ();
    }
}

