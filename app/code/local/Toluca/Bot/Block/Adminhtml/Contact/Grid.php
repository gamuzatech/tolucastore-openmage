<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Contact_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botContactGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('bot/contact')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('bot')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('type_id', array(
		    'header'  => Mage::helper ('bot')->__('Type'),
		    'index'   => 'type_id',
            'type'    => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_bot_type')->toArray (),
		));
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('bot')->__('Name'),
		    'index'   => 'name',
		));
		$this->addColumn ('number', array(
		    'header'  => Mage::helper ('bot')->__('Number'),
		    'index'   => 'number',
		));
		$this->addColumn ('is_business', array(
		    'header'  => Mage::helper ('bot')->__('Business'),
		    'index'   => 'is_business',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_enterprise', array(
		    'header'  => Mage::helper ('bot')->__('Enterprise'),
		    'index'   => 'is_enterprise',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_me', array(
		    'header'  => Mage::helper ('bot')->__('Is Me'),
		    'index'   => 'is_me',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_psa', array(
		    'header'  => Mage::helper ('bot')->__('PSA'),
		    'index'   => 'is_psa',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_user', array(
		    'header'  => Mage::helper ('bot')->__('User'),
		    'index'   => 'is_user',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_my_contact', array(
		    'header'  => Mage::helper ('bot')->__('My Contact'),
		    'index'   => 'is_my_contact',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_wa_contact', array(
		    'header'  => Mage::helper ('bot')->__('WA Contact'),
		    'index'   => 'is_wa_contact',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_active', array(
		    'header'  => Mage::helper ('bot')->__('Is Active'),
		    'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('bot')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('bot')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
		));

        $this->addExportType('*/*/exportCsv', Mage::helper('bot')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bot')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

    protected function _prepareMassaction ()
    {
        $this->setMassactionIdField ('entity_id');
        $this->getMassactionBlock ()->setFormFieldName ('contact');

        $statuses = Mage::getSingleton ('adminhtml/system_config_source_yesno')->toOptionArray ();

        array_unshift ($statuses, array ('label' => '', 'value' => ''));

        $this->getMassactionBlock ()->addItem ('status', array(
            'label' => Mage::helper ('adminhtml')->__('Change status'),
            'url'   => $this->getUrl ('*/*/massStatus', array ('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper ('adminhtml')->__('Enabled'),
                    'values' => $statuses
                )
            )
        ));

        return parent::_prepareMassaction ();
    }

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

