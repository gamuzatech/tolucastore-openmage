<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Nfce_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilNfceGrid');
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
		$collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
        ;

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('brazil')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
            'filter_index' => 'main_table.entity_id',
		));
/*
		$this->addColumn ('order_id', array(
		    'header'  => Mage::helper ('brazil')->__('Order ID'),
		    'align'   => 'right',
		    'index'   => 'order_id',
		));
*/
		$this->addColumn ('batch_id', array(
		    'header'  => Mage::helper ('brazil')->__('Batch ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'batch_id',
		));
		$this->addColumn ('country_id', array(
		    'header'  => Mage::helper ('brazil')->__('Country ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'country_id',
		));
		$this->addColumn ('region_id', array(
		    'header'  => Mage::helper ('brazil')->__('Region ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'region_id',
		));
		$this->addColumn ('number_id', array(
		    'header'  => Mage::helper ('brazil')->__('Number ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'number_id',
		));
		$this->addColumn ('operation_id', array(
		    'header'  => Mage::helper ('brazil')->__('Operation Type'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'operation_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_operation')->toArray (),
		));
		$this->addColumn ('destiny_id', array(
		    'header'  => Mage::helper ('brazil')->__('Destiny'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'destiny_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_destiny')->toArray (),
		));
		$this->addColumn ('city_id', array(
		    'header'  => Mage::helper ('brazil')->__('City ID'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'city_id',
		));
		$this->addColumn ('print_id', array(
		    'header'  => Mage::helper ('brazil')->__('Print'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'print_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_print')->toArray (),
		));
		$this->addColumn ('emission_id', array(
		    'header'  => Mage::helper ('brazil')->__('Emission'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'emission_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_emission')->toArray (),
		));
		$this->addColumn ('environment_id', array(
		    'header'  => Mage::helper ('brazil')->__('Environment'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'environment_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_environment')->toArray (),
		));
		$this->addColumn ('finality_id', array(
		    'header'  => Mage::helper ('brazil')->__('Finality'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'finality_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_finality')->toArray (),
		));
		$this->addColumn ('consumer_id', array(
		    'header'  => Mage::helper ('brazil')->__('Consumer'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'consumer_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_consumer')->toArray (),
		));
		$this->addColumn ('presence_id', array(
		    'header'  => Mage::helper ('brazil')->__('Presence'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'presence_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_presence')->toArray (),
		));
		$this->addColumn ('intermediary_id', array(
		    'header'  => Mage::helper ('brazil')->__('Intermediary'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'intermediary_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_intermediary')->toArray (),
		));
		$this->addColumn ('process_id', array(
		    'header'  => Mage::helper ('brazil')->__('Process'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'process_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_process')->toArray (),
		));
		$this->addColumn ('freight_id', array(
		    'header'  => Mage::helper ('brazil')->__('Freight'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'freight_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_freight')->toArray (),
		));
		$this->addColumn ('crt_id', array(
		    'header'  => Mage::helper ('brazil')->__('CRT'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'crt_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_crt')->toArray (),
		));
		$this->addColumn ('version_id', array(
		    'header'  => Mage::helper ('brazil')->__('Version'),
		    'align'   => 'right',
		    'index'   => 'version_id',
		));
		$this->addColumn ('response_id', array(
		    'header'  => Mage::helper ('brazil')->__('Response'),
		    'align'   => 'right',
		    'index'   => 'response_id',
		));
		$this->addColumn ('receipt_id', array(
		    'header'  => Mage::helper ('brazil')->__('Receipt'),
		    'align'   => 'right',
		    'index'   => 'receipt_id',
		));
		$this->addColumn ('average_id', array(
		    'header'  => Mage::helper ('brazil')->__('Average'),
		    'align'   => 'right',
		    'index'   => 'average_id',
		));
		$this->addColumn ('code', array(
		    'header'  => Mage::helper ('brazil')->__('Code'),
		    'align'   => 'right',
		    'index'   => 'code',
		));
		$this->addColumn ('key', array(
		    'header'  => Mage::helper ('brazil')->__('Key'),
		    'align'   => 'right',
		    'index'   => 'Key',
		));
		$this->addColumn ('digit', array(
		    'header'  => Mage::helper ('brazil')->__('Digit'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'digit',
		));
		$this->addColumn ('operation_name', array(
		    'header'  => Mage::helper ('brazil')->__('Operation Description'),
		    'align'   => 'right',
		    'index'   => 'operation_name',
		));
		$this->addColumn ('model_id', array(
		    'header'  => Mage::helper ('brazil')->__('Model'),
		    'align'   => 'right',
		    'index'   => 'model_id',
		));
		$this->addColumn ('series_id', array(
		    'header'  => Mage::helper ('brazil')->__('Series'),
		    'align'   => 'right',
		    'index'   => 'series_id',
		));
		$this->addColumn ('company_taxvat', array(
		    'header'  => Mage::helper ('brazil')->__('Company Taxvat'),
		    'align'   => 'right',
		    'index'   => 'company_taxvat',
		));
		$this->addColumn ('company_ie', array(
		    'header'  => Mage::helper ('brazil')->__('Company IE'),
		    'align'   => 'right',
		    'index'   => 'company_ie',
		));
		$this->addColumn ('customer_taxvat', array(
		    'header'  => Mage::helper ('brazil')->__('Customer Taxvat'),
		    'align'   => 'right',
		    'index'   => 'customer_taxvat',
		));
		$this->addColumn ('customer_ie', array(
		    'header'  => Mage::helper ('brazil')->__('Customer I.E.'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'customer_ie',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_customer_ie')->toArray (),
		));
		$this->addColumn ('customer_email', array(
		    'header'  => Mage::helper ('brazil')->__('Customer Email'),
		    'align'   => 'right',
		    'index'   => 'customer_email',
		));
		$this->addColumn ('customer_firstname', array(
		    'header'  => Mage::helper ('brazil')->__('Customer Firstname'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'customer_firstname',
		));
		$this->addColumn ('customer_lastname', array(
		    'header'  => Mage::helper ('brazil')->__('Customer Lastname'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'customer_lastname',
		));
/*
		$this->addColumn ('payment_method', array(
		    'header'  => Mage::helper ('brazil')->__('Payment'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'payment_method',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_payment_type')->toArray (),
		));
		$this->addColumn ('payment_amount', array(
		    'header'  => Mage::helper ('brazil')->__('Amount'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'payment_amount',
            'type'    => 'price',
            'currency_code' => $store->getBaseCurrency ()->getCode (),
		));
*/
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('brazil')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
            'filter_index' => 'main_table.created_at',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('brazil')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
            'width'  => '100px',
            'filter_index' => 'main_table.updated_at',
		));
		$this->addColumn ('emission_at', array(
			'header' => Mage::helper ('brazil')->__('Emission At'),
			'index'  => 'emission_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('response_at', array(
			'header' => Mage::helper ('brazil')->__('Response At'),
			'index'  => 'response_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('response_application', array(
		    'header'  => Mage::helper ('brazil')->__('Application'),
		    'align'   => 'right',
		    'index'   => 'response_application',
		));
		$this->addColumn ('response_reason', array(
		    'header'  => Mage::helper ('brazil')->__('Reason'),
		    'align'   => 'right',
		    'index'   => 'response_reason',
		));
		$this->addColumn ('response_key', array(
		    'header'  => Mage::helper ('brazil')->__('Key'),
		    'align'   => 'right',
		    'index'   => 'response_key',
		));

        $this->addColumn ('action', array(
            'header' => Mage::helper ('brazil')->__('Order'),
            'width'  => '50px',
            'type'   => 'action',
            'getter' => 'getOrderId',
            'index'  => 'stores',
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
            'actions' => array(
                array(
                    'caption' => Mage::helper ('brazil')->__('View'),
                    'url'     => array ('base' => 'adminhtml/sales_order/view'),
                    'field'   => 'order_id',
                    'data-column' => 'action',
                ),
            ),
        ));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing
	}
}

