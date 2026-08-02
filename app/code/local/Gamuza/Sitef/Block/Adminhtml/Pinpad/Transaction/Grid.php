<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Block_Adminhtml_Pinpad_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('sitefPinpadTransactionGrid');
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
		$collection = Mage::getModel ('sitef/pinpad_transaction')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('sitef')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('store_id', array(
		    'header'  => Mage::helper ('sitef')->__('Store'),
		    'align'   => 'right',
		    'index'   => 'store_id',
	        'type'    => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getStoreOptionHash (true),
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('sitef')->__('Customer'),
		    'align'   => 'right',
		    'index'   => 'customer_id',
	        'type'    => 'options',
		    'options' => self::_getCustomers (),
		));
/*
		$this->addColumn ('order_id', array(
		    'header'  => Mage::helper ('sitef')->__('Order ID'),
		    'align'   => 'right',
		    'index'   => 'order_id',
		));
*/
		$this->addColumn ('order_increment_id', array(
		    'header'  => Mage::helper ('sitef')->__('Order Inc. ID'),
		    'align'   => 'right',
		    'index'   => 'order_increment_id',
		));

		$this->addColumn ('operator_id', array(
		    'header'  => Mage::helper ('sitef')->__('Operator'),
		    'align'   => 'right',
		    'index'   => 'operator_id',
	        'type'    => 'options',
		    'options' => self::_getOperators (),
		));
		$this->addColumn ('merchant_id', array(
		    'header'  => Mage::helper ('sitef')->__('Merchant'),
		    'index'   => 'merchant_id',
		));
		$this->addColumn ('terminal_id', array(
		    'header'  => Mage::helper ('sitef')->__('Terminal'),
		    'index'   => 'terminal_id',
		));
		$this->addColumn ('payment_id', array(
		    'header'  => Mage::helper ('sitef')->__('Payment'),
		    'index'   => 'payment_id',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_payment_method')->toArray (),
		));
		$this->addColumn ('payment_confirmation', array(
		    'header'  => Mage::helper ('sitef')->__('Confirmation'),
		    'index'   => 'payment_confirmation',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
        $this->addColumn('payment_amount', array(
            'header'  => Mage::helper('sitef')->__('Amount'),
            'type'    => 'price',
            'index'   => 'payment_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
        ));

		$this->addColumn ('authorization_data', array(
		    'header'  => Mage::helper ('sitef')->__('Authorization'),
		    'index'   => 'authorization_data',
		));
		$this->addColumn ('transaction_data', array(
		    'header'  => Mage::helper ('sitef')->__('Transaction'),
		    'index'   => 'transaction_data',
		));
		$this->addColumn ('function_id', array(
		    'header'  => Mage::helper ('sitef')->__('Function'),
		    'align'   => 'right',
		    'width'   => '50px',
		    'type'    => 'number',
		    'index'   => 'function_id',
		));
		$this->addColumn ('payment_type', array(
		    'header'  => Mage::helper ('sitef')->__('Type'),
		    'index'   => 'payment_type',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_payment_type')->toArray (),
		));
		$this->addColumn ('payment_name', array(
		    'header'  => Mage::helper ('sitef')->__('Name'),
		    'index'   => 'payment_name',
		));
		$this->addColumn ('payment_description', array(
		    'header'  => Mage::helper ('sitef')->__('Description'),
		    'index'   => 'payment_description',
		));
		$this->addColumn ('transaction_datetime', array(
		    'header'  => Mage::helper ('sitef')->__('Datetime'),
		    'index'   => 'transaction_datetime',
		));
		$this->addColumn ('receipt_type', array(
		    'header'  => Mage::helper ('sitef')->__('Receipt'),
		    'index'   => 'receipt_type',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_receipt_type')->toArray (),
		));
		$this->addColumn ('authorizer_id', array(
		    'header'  => Mage::helper ('sitef')->__('Authorizer'),
		    'index'   => 'authorizer_id',
		));
		$this->addColumn ('card_brand', array(
		    'header'  => Mage::helper ('sitef')->__('Brand'),
		    'index'   => 'card_brand',
		));
		$this->addColumn ('sitef_nsu', array(
		    'header'  => Mage::helper ('sitef')->__('NSU'),
		    'index'   => 'sitef_nsu',
		));
		$this->addColumn ('host_nsu', array(
		    'header'  => Mage::helper ('sitef')->__('Host NSU'),
		    'index'   => 'host_nsu',
		));
		$this->addColumn ('authorization_code', array(
		    'header'  => Mage::helper ('sitef')->__('Authorization'),
		    'index'   => 'authorization_code',
		));
		$this->addColumn ('card_bin', array(
		    'header'  => Mage::helper ('sitef')->__('BIN'),
		    'index'   => 'card_bin',
		));
		$this->addColumn ('institution_name', array(
		    'header'  => Mage::helper ('sitef')->__('Institution'),
		    'index'   => 'institution_name',
		));
		$this->addColumn ('establishment_id', array(
		    'header'  => Mage::helper ('sitef')->__('Establishment'),
		    'index'   => 'establishment_id',
		));
		$this->addColumn ('authorizer_network_id', array(
		    'header'  => Mage::helper ('sitef')->__('Network'),
		    'index'   => 'authorizer_network_id',
		));
		$this->addColumn ('payment_sequence', array(
		    'header' => Mage::helper ('sitef')->__('Sequence'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'payment_sequence',
		));
		$this->addColumn ('gerpdv_data', array(
		    'header'  => Mage::helper ('sitef')->__('GerPDV'),
		    'index'   => 'gerpdv_data',
		));
		$this->addColumn ('nfce_card_brand', array(
		    'header'  => Mage::helper ('sitef')->__('NFC-e'),
		    'index'   => 'nfce_card_brand',
		));
		$this->addColumn ('nfce_authorization_number', array(
		    'header'  => Mage::helper ('sitef')->__('NFC-e'),
		    'index'   => 'nfce_authorization_number',
		));
		$this->addColumn ('card_expiration_date', array(
		    'header'  => Mage::helper ('sitef')->__('Expiration'),
		    'index'   => 'card_expiration_date',
		));
		$this->addColumn ('card_holder_name', array(
		    'header'  => Mage::helper ('sitef')->__('Holder'),
		    'index'   => 'card_holder_name',
		));
		$this->addColumn ('card_last_digits', array(
		    'header'  => Mage::helper ('sitef')->__('Digits'),
		    'index'   => 'card_last_digits',
		));
		$this->addColumn ('authorization_response_code', array(
		    'header'  => Mage::helper ('sitef')->__('Response'),
		    'index'   => 'authorization_response_code',
		));
		$this->addColumn ('card_number', array(
		    'header'  => Mage::helper ('sitef')->__('Number'),
		    'index'   => 'card_number',
		));
		$this->addColumn ('card_holder_birth_date', array(
		    'header'  => Mage::helper ('sitef')->__('Birth'),
		    'index'   => 'card_holder_birth_date',
		));
		$this->addColumn ('card_name', array(
		    'header'  => Mage::helper ('sitef')->__('Name'),
		    'index'   => 'card_name',
		));
		$this->addColumn ('card_read_type', array(
		    'header'  => Mage::helper ('sitef')->__('Type'),
		    'index'   => 'card_read_type',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_card_read_type')->toArray (),
		));
		$this->addColumn ('card_read_status', array(
		    'header'  => Mage::helper ('sitef')->__('Status'),
		    'index'   => 'card_read_status',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_card_read_status')->toArray (),
		));
		$this->addColumn ('transaction_identifier_nit', array(
		    'header'  => Mage::helper ('sitef')->__('NIT'),
		    'index'   => 'transaction_identifier_nit',
		));
		$this->addColumn ('debit_bill_payment_supported', array(
		    'header'  => Mage::helper ('sitef')->__('Debit Bill'),
		    'index'   => 'debit_bill_payment_supported',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('credit_bill_payment_supported', array(
		    'header'  => Mage::helper ('sitef')->__('Credit Bill'),
		    'index'   => 'credit_bill_payment_supported',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('sensitive_fields_collect', array(
		    'header'  => Mage::helper ('sitef')->__('Collect'),
		    'index'   => 'sensitive_fields_collect',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('sensitive_fields_begin', array(
		    'header'  => Mage::helper ('sitef')->__('Begin'),
		    'index'   => 'sensitive_fields_begin',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('sensitive_fields_end', array(
		    'header'  => Mage::helper ('sitef')->__('End'),
		    'index'   => 'sensitive_fields_end',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('paper_signature_required', array(
		    'header'  => Mage::helper ('sitef')->__('Paper'),
		    'index'   => 'paper_signature_required',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));

		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('sitef')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('sitef/adminhtml_system_config_source_payment_status')->toArray (),
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('sitef')->__('Message'),
		    'index'   => 'message',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('sitef')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('sitef')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('authorized_at', array(
			'header' => Mage::helper ('sitef')->__('Authorized At'),
			'index'  => 'authorized_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('canceled_at', array(
			'header' => Mage::helper ('sitef')->__('Canceled At'),
			'index'  => 'canceled_at',
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

	public function _getOperators ()
    {
		if (!Mage::helper ('core')->isModuleEnabled ('Toluca_PDV'))
		{
			return array ();
		}

        $collection = Mage::getModel ('pdv/operator')->getCollection ();

        $collection->getSelect ()->reset (Zend_Db_Select::COLUMNS)
            ->columns (array ('id' => 'entity_id', 'name' => 'name'))
        ;

        return $collection->toOptionHash ();
    }
}

