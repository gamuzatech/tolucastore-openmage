<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('picpayTransactionGrid');
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
		$collection = Mage::getModel ('picpay/transaction')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('picpay')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('store_id', array(
		    'header'  => Mage::helper ('picpay')->__('Store'),
		    'align'   => 'right',
		    'index'   => 'store_id',
	        'type'    => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getStoreOptionHash (true),
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('picpay')->__('Customer'),
		    'align'   => 'right',
		    'index'   => 'customer_id',
	        'type'    => 'options',
		    'options' => self::_getCustomers (),
		));
/*
		$this->addColumn ('order_id', array(
		    'header'  => Mage::helper ('picpay')->__('Order ID'),
		    'align'   => 'right',
		    'index'   => 'order_id',
		));
*/
		$this->addColumn ('order_increment_id', array(
		    'header'  => Mage::helper ('picpay')->__('Order Inc. ID'),
		    'align'   => 'right',
		    'index'   => 'order_increment_id',
		));
/*
		$this->addColumn ('callback_url', array(
		    'header'  => Mage::helper ('picpay')->__('Callback URL'),
		    'index'   => 'callback_url',
		));
		$this->addColumn ('return_url', array(
		    'header'  => Mage::helper ('picpay')->__('Return URL'),
		    'index'   => 'return_url',
		));
*/
        $this->addColumn('amount', array(
            'header'  => Mage::helper('picpay')->__('Amount'),
            'type'    => 'price',
            'index'   => 'amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
        ));
		$this->addColumn ('expires_at', array(
			'header' => Mage::helper ('picpay')->__('Expires At'),
			'index'  => 'expires_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
/*
		$this->addColumn ('buyer_email', array(
		    'header'  => Mage::helper ('picpay')->__('Buyer E-mail'),
		    'index'   => 'buyer_email',
		));
*/
		$this->addColumn ('payment_url', array(
		    'header'  => Mage::helper ('picpay')->__('Payment URL'),
		    'index'   => 'payment_url',
		));
		$this->addColumn ('authorization_id', array(
		    'header'  => Mage::helper ('picpay')->__('Authorization ID'),
		    'index'   => 'authorization_id',
		));
		$this->addColumn ('cancellation_id', array(
		    'header'  => Mage::helper ('picpay')->__('Cancellation ID'),
		    'index'   => 'cancellation_id',
		));
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('picpay')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('picpay/adminhtml_system_config_source_payment_status')->toArray (),
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('picpay')->__('Message'),
		    'index'   => 'message',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('picpay')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('picpay')->__('Updated At'),
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

