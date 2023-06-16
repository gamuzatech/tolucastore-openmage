<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('openpixTransactionGrid');
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
		$collection = Mage::getModel ('openpix/transaction')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('openpix')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('store_id', array(
		    'header'  => Mage::helper ('openpix')->__('Store'),
		    'align'   => 'right',
		    'index'   => 'store_id',
	        'type'    => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getStoreOptionHash (true),
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('openpix')->__('Customer'),
		    'align'   => 'right',
		    'index'   => 'customer_id',
	        'type'    => 'options',
		    'options' => self::_getCustomers (),
		));
/*
		$this->addColumn ('order_id', array(
		    'header'  => Mage::helper ('openpix')->__('Order ID'),
		    'align'   => 'right',
		    'index'   => 'order_id',
		));
*/
		$this->addColumn ('order_increment_id', array(
		    'header'  => Mage::helper ('openpix')->__('Order Inc. ID'),
		    'align'   => 'right',
		    'index'   => 'order_increment_id',
		));
		$this->addColumn ('correlation_id', array(
		    'header'  => Mage::helper ('openpix')->__('Correlation ID'),
		    'align'   => 'right',
		    'index'   => 'correlation_id',
		));
		$this->addColumn ('transaction_id', array(
		    'header'  => Mage::helper ('openpix')->__('Transaction ID'),
		    'align'   => 'right',
		    'index'   => 'transaction_id',
		));
        $this->addColumn('amount', array(
            'header'  => Mage::helper ('openpix')->__('Amount'),
            'align'   => 'right',
            'index'   => 'amount',
        ));
        $this->addColumn('expires_in', array(
            'header'  => Mage::helper ('openpix')->__('Expires In'),
            'align'   => 'right',
            'index'   => 'expires_in',
        ));
/*
		$this->addColumn ('payment_link_id', array(
		    'header'  => Mage::helper ('openpix')->__('Payment Link ID'),
		    'index'   => 'payment_link_id',
		));
*/
		$this->addColumn ('payment_link_url', array(
		    'header'  => Mage::helper ('openpix')->__('Payment Link URL'),
		    'index'   => 'payment_link_url',
		));
/*
		$this->addColumn ('qrcode_image_url', array(
		    'header'  => Mage::helper ('openpix')->__('QRCode Image URL'),
		    'index'   => 'qrcode_image_url',
		));
		$this->addColumn ('brcode_url', array(
		    'header'  => Mage::helper ('openpix')->__('BRCode URL'),
		    'index'   => 'brcode_url',
		));
*/
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('openpix')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('openpix/adminhtml_system_config_source_payment_status')->toArray (),
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('openpix')->__('Message'),
		    'index'   => 'message',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('openpix')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('openpix')->__('Updated At'),
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

