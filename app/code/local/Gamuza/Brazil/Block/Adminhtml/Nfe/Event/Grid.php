<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Nfe_Event_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilNfeEventGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
        $nfe = Mage::registry ('current_nfe');

		$collection = Mage::getModel ('brazil/nfe_event')->getCollection ()
            ->addFieldToFilter ('nfe_id', array ('eq' => $nfe->getId ()))
        ;

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
/*
		$this->addColumn ('nfe_id', array(
		    'header'  => Mage::helper ('brazil')->__('NF-e ID'),
		    'align'   => 'right',
		    'index'   => 'nfe_id',
		));
*/
		$this->addColumn ('application', array(
		    'header'  => Mage::helper ('brazil')->__('Application'),
		    'align'   => 'right',
		    'index'   => 'application',
		));
		$this->addColumn ('reason', array(
		    'header'  => Mage::helper ('brazil')->__('Reason'),
		    'align'   => 'right',
		    'index'   => 'reason',
		));
		$this->addColumn ('key', array(
		    'header'  => Mage::helper ('brazil')->__('Key'),
		    'align'   => 'right',
		    'index'   => 'key',
		));
		$this->addColumn ('process_id', array(
		    'header'  => Mage::helper ('brazil')->__('Process'),
		    'align'   => 'right',
		    'index'   => 'process_id',
		));
		$this->addColumn ('received_id', array(
		    'header'  => Mage::helper ('brazil')->__('Received'),
		    'align'   => 'right',
		    'index'   => 'received_id',
		));
		$this->addColumn ('protocol_id', array(
		    'header'  => Mage::helper ('brazil')->__('Protocol'),
		    'align'   => 'right',
		    'index'   => 'protocol_id',
		));
		$this->addColumn ('type_id', array(
		    'header'  => Mage::helper ('brazil')->__('Type'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'type_id',
		));
		$this->addColumn ('sequence_id', array(
		    'header'  => Mage::helper ('brazil')->__('Sequence'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'sequence_id',
		));
		$this->addColumn ('event_id', array(
		    'header'  => Mage::helper ('brazil')->__('Event'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'event_id',
		));
		$this->addColumn ('organ_id', array(
		    'header'  => Mage::helper ('brazil')->__('Organ'),
		    'align'   => 'right',
            'width'   => '50px',
		    'index'   => 'organ_id',
            'type'    => 'options',
            'options' => Mage::getModel ('brazil/adminhtml_system_config_source_ibge_region')->toArray (),
		));
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('brazil')->__('Name'),
		    'align'   => 'right',
		    'index'   => 'name',
		));
		$this->addColumn ('description', array(
		    'header'  => Mage::helper ('brazil')->__('Description'),
		    'align'   => 'right',
		    'index'   => 'description',
		));
		$this->addColumn ('justification', array(
		    'header'  => Mage::helper ('brazil')->__('Justification'),
		    'align'   => 'right',
		    'index'   => 'justification',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('brazil')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
/*
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('brazil')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
*/
		$this->addColumn ('emitted_at', array(
			'header' => Mage::helper ('brazil')->__('Emitted At'),
			'index'  => 'emitted_at',
            'type'   => 'datetime',
            'width'  => '100px',
		));
		$this->addColumn ('received_at', array(
			'header' => Mage::helper ('brazil')->__('Received At'),
			'index'  => 'received_at',
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

