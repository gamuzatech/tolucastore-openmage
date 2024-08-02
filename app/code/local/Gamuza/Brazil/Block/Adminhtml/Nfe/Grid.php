<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Nfe_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilNfeGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('brazil/nfe')->getCollection ()
            ->addOrderInfo ()
        ;

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $this->addColumn ('status_color', array(
            'header'   => Mage::helper ('brazil')->__('Color'),
            'index'    => 'status_id',
            'type'     => 'options',
            'width'    => '70px',
            'options'  => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_status')->toArray (),
            'renderer' => 'brazil/adminhtml_widget_grid_column_renderer_color',
        ));
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
		$this->addColumn ('increment_id', array(
		    'header'  => Mage::helper ('brazil')->__('Order Inc. ID'),
		    'align'   => 'right',
		    'index'   => 'increment_id',
		));
		$this->addColumn ('batch_id', array(
		    'header'  => Mage::helper ('brazil')->__('Batch ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'batch_id',
		));
		$this->addColumn ('number_id', array(
		    'header'  => Mage::helper ('brazil')->__('Number ID'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'number_id',
		));
		$this->addColumn ('code', array(
		    'header'  => Mage::helper ('brazil')->__('Code'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'code',
		));
		$this->addColumn ('key', array(
		    'header'  => Mage::helper ('brazil')->__('Key'),
		    'align'   => 'right',
		    'index'   => 'key',
		));
		$this->addColumn ('digit', array(
		    'header'  => Mage::helper ('brazil')->__('Digit'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'digit',
		));
		$this->addColumn ('model_id', array(
		    'header'  => Mage::helper ('brazil')->__('Model'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'model_id',
            'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_dfe_model')->toArray (),
		));
		$this->addColumn ('series_id', array(
		    'header'  => Mage::helper ('brazil')->__('Series'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'series_id',
		));
		$this->addColumn ('version', array(
		    'header'  => Mage::helper ('brazil')->__('Version'),
		    'align'   => 'right',
		    'index'   => 'version',
            'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_version')->toArray (),
		));
		$this->addColumn ('crt_id', array(
		    'header'  => Mage::helper ('brazil')->__('CRT'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'crt_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_crt')->toArray (),
		));
		$this->addColumn ('status_id', array(
		    'header'  => Mage::helper ('brazil')->__('Status'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'status_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_status')->toArray (),
		));
		$this->addColumn ('operation_name', array(
		    'header'  => Mage::helper ('brazil')->__('Operation Description'),
		    'align'   => 'right',
		    'index'   => 'operation_name',
		));
		$this->addColumn ('country_id', array(
		    'header'  => Mage::helper ('brazil')->__('Country'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'country_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_ibge_country')->toArray (),
		));
		$this->addColumn ('region_id', array(
		    'header'  => Mage::helper ('brazil')->__('Region'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'region_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_ibge_region')->toArray (),
		));
		$this->addColumn ('city_id', array(
		    'header'  => Mage::helper ('brazil')->__('City'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'city_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_ibge_city')->toArray (),
		));
		$this->addColumn ('operation_id', array(
		    'header'  => Mage::helper ('brazil')->__('Operation Type'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'operation_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_operation')->toArray (),
		));
		$this->addColumn ('destiny_id', array(
		    'header'  => Mage::helper ('brazil')->__('Destiny'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'destiny_id',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_destiny')->toArray (),
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
		$this->addColumn ('customer_taxvat', array(
		    'header'  => Mage::helper ('brazil')->__('Customer Taxvat'),
		    'align'   => 'right',
		    'index'   => 'customer_taxvat',
		));
		$this->addColumn ('customer_rg_ie', array(
		    'header'  => Mage::helper ('brazil')->__('Customer RG/IE'),
		    'align'   => 'right',
		    'index'   => 'customer_rg_ie',
		));
		$this->addColumn ('customer_ie_icms', array(
		    'header'  => Mage::helper ('brazil')->__('Customer IE/ICMS'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'customer_ie_icms',
	        'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_nfe_customer_ie_icms')->toArray (),
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
		$this->addColumn ('observation', array(
			'header' => Mage::helper ('brazil')->__('Observation'),
            'align'  => 'right',
			'index'  => 'observation',
		));
/*
		$this->addColumn ('fisco', array(
			'header' => Mage::helper ('brazil')->__('Fisco'),
            'align'  => 'right',
			'index'  => 'fisco',
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
		$this->addColumn ('signed_at', array(
			'header' => Mage::helper ('brazil')->__('Signed At'),
			'index'  => 'signed_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));

        $this->addColumn ('response_action', array(
            'header' => Mage::helper ('brazil')->__('Response'),
            'width'  => '50px',
            'type'   => 'action',
            'getter' => 'getId',
            'index'  => 'stores',
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
            'actions' => array(
                array(
                    'caption' => Mage::helper ('brazil')->__('View'),
                    'url'     => array ('base' => 'admin_brazil/adminhtml_nfe/response'),
                    'field'   => 'nfe_id',
                    'data-column' => 'action',
                ),
            ),
        ));
        $this->addColumn ('event_action', array(
            'header' => Mage::helper ('brazil')->__('Event'),
            'width'  => '50px',
            'type'   => 'action',
            'getter' => 'getId',
            'index'  => 'stores',
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
            'actions' => array(
                array(
                    'caption' => Mage::helper ('brazil')->__('View'),
                    'url'     => array ('base' => 'admin_brazil/adminhtml_nfe/event'),
                    'field'   => 'nfe_id',
                    'data-column' => 'action',
                ),
            ),
        ));
        $this->addColumn ('order_action', array(
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

