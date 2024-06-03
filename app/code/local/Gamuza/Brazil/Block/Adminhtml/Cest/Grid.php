<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Cest_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilCestGrid');
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
		$collection = Mage::getModel ('brazil/cest')->getCollection ();

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
		$this->addColumn ('code', array(
		    'header'  => Mage::helper ('brazil')->__('Code'),
		    'align'   => 'right',
		    'index'   => 'code',
		));
		$this->addColumn ('description', array(
		    'header'  => Mage::helper ('brazil')->__('Description'),
		    'align'   => 'right',
		    'index'   => 'description',
		));
		$this->addColumn ('version', array(
		    'header'  => Mage::helper ('brazil')->__('Version'),
		    'align'   => 'right',
		    'index'   => 'version',
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
            'filter_index' => 'main_table.created_at',
		));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing
	}
}

