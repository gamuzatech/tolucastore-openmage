<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Ibpt_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilIbptGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('brazil/ibpt')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('brazil')->__('ID'),
		    'align'  => 'right',
		    'width'  => '50px',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('code', array(
		    'header'  => Mage::helper ('brazil')->__('Code'),
		    'align'   => 'right',
		    'index'   => 'code',
		));
		$this->addColumn ('exception', array(
		    'header'  => Mage::helper ('brazil')->__('Exception'),
		    'align'   => 'right',
		    'index'   => 'exception',
		));
		$this->addColumn ('type', array(
		    'header'  => Mage::helper ('brazil')->__('Type'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'type',
            'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_ibpt_type')->toArray (),
		));
		$this->addColumn ('description', array(
		    'header'  => Mage::helper ('brazil')->__('Description'),
		    'align'   => 'right',
		    'index'   => 'description',
		));
		$this->addColumn ('national_federal', array(
		    'header'  => Mage::helper ('brazil')->__('National Federal'),
		    'align'   => 'right',
            'type'    => 'number',
		    'index'   => 'national_federal',
		));
		$this->addColumn ('imported_federal', array(
		    'header'  => Mage::helper ('brazil')->__('Imported Federal'),
		    'align'   => 'right',
            'type'    => 'number',
		    'index'   => 'imported_federal',
		));
		$this->addColumn ('state', array(
		    'header'  => Mage::helper ('brazil')->__('State'),
		    'align'   => 'right',
            'type'    => 'number',
		    'index'   => 'state',
		));
		$this->addColumn ('local', array(
		    'header'  => Mage::helper ('brazil')->__('Local'),
		    'align'   => 'right',
            'type'    => 'number',
		    'index'   => 'local',
		));
		$this->addColumn ('key', array(
		    'header'  => Mage::helper ('brazil')->__('Key'),
		    'align'   => 'right',
		    'index'   => 'key',
		));
		$this->addColumn ('version', array(
		    'header'  => Mage::helper ('brazil')->__('Version'),
		    'align'   => 'right',
		    'index'   => 'version',
		));
		$this->addColumn ('source', array(
		    'header'  => Mage::helper ('brazil')->__('Source'),
		    'align'   => 'right',
		    'index'   => 'source',
		));
		$this->addColumn ('begin_at', array(
			'header' => Mage::helper ('brazil')->__('Begin At'),
			'index'  => 'begin_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('end_at', array(
			'header' => Mage::helper ('brazil')->__('End At'),
			'index'  => 'end_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('brazil')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing
	}
}

