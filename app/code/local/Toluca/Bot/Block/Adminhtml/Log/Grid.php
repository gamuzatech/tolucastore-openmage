<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botLogGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
        $queue = Mage::registry ('current_queue');

		$collection = Mage::getModel ('bot/log')->getCollection ()
            ->addFieldToFilter ('queue_id', array ('eq' => $queue->getId ()))
        ;

        $collection->getSelect ()
            ->join (
                array ('promotion' => Mage::getSingleton ('core/resource')->getTableName ('bot/promotion')),
                'main_table.promotion_id = promotion.entity_id',
                array (
                    'promotion_name' => 'promotion.name',
                )
            )
            ->join (
                array ('contact' => Mage::getSingleton ('core/resource')->getTableName ('bot/contact')),
                'main_table.contact_id = contact.entity_id',
                array (
                    'contact_name'   => 'contact.name',
                    'contact_number' => 'contact.number',
                )
            )
        ;

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
            'filter_index' => 'main_table.entity_id',
		));
		$this->addColumn ('promotion_id', array(
		    'header' => Mage::helper ('bot')->__('Promotion ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'promotion_id',
		));
		$this->addColumn ('promotion_name', array(
		    'header' => Mage::helper ('bot')->__('Promotion Name'),
		    'index'  => 'promotion_name',
		    'filter_index' => 'promotion.name',
		));
		$this->addColumn ('queue_id', array(
		    'header' => Mage::helper ('bot')->__('Queue ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'queue_id',
		));
		$this->addColumn ('contact_id', array(
		    'header' => Mage::helper ('bot')->__('Contact ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'contact_id',
		));
		$this->addColumn ('contact_name', array(
		    'header' => Mage::helper ('bot')->__('Contact Name'),
		    'index'  => 'contact_name',
		    'filter_index' => 'contact.name',
		));
		$this->addColumn ('contact_number', array(
		    'header' => Mage::helper ('bot')->__('Contact Number'),
		    'index'  => 'contact_number',
		    'filter_index' => 'contact.number',
		));
		$this->addColumn ('is_delivered', array(
		    'header'  => Mage::helper ('bot')->__('Delivered'),
		    'index'   => 'is_delivered',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_error', array(
		    'header'  => Mage::helper ('bot')->__('Error'),
		    'index'   => 'is_error',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('bot')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
            'filter_index' => 'main_table.created_at',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('bot')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
            'filter_index' => 'main_table.updated_at',
		));

        $this->addColumn ('action', array(
            'header'   => Mage::helper ('bot')->__('Action'),
            'width'    => '50px',
            'type'     => 'action',
            'getter'   => 'getPromotionId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('bot')->__('Promotion'),
                    'field'   => 'id',
                    'url'     => array(
                        'base'   => '*/adminhtml_promotion/edit',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                ),
            ),
        ));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

