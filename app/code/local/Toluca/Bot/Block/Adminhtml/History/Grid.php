<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_History_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botHistoryGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
        $chat = Mage::registry ('current_chat');

		$collection = Mage::getModel ('bot/message')->getCollection ()
            ->addFieldToFilter ('chat_id', array ('eq' => $chat->getId ()))
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
		));
		$this->addColumn ('chat_id', array(
		    'header' => Mage::helper ('bot')->__('Chat ID'),
		    'align'  => 'right',
		    'index'  => 'chat_id',
            'type'   => 'number',
		));
		$this->addColumn ('bot_type', array(
		    'header' => Mage::helper ('bot')->__('Type'),
		    'index'  => 'bot_type',
            'type'   => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_bot_type')->toArray (),
		));
		$this->addColumn ('phone', array(
		    'header'  => Mage::helper ('bot')->__('Phone'),
		    'index'   => 'phone',
            'width'   => '120px',
		));
		$this->addColumn ('type_id', array(
		    'header' => Mage::helper ('bot')->__('Type'),
		    'index'  => 'type_id',
            'type'   => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_message_type')->toArray (),
		));
/*
		$this->addColumn ('remote_ip', array(
		    'header' => Mage::helper ('bot')->__('Remote IP'),
		    'index'  => 'remote_ip',
		));
		$this->addColumn ('email', array(
		    'header'  => Mage::helper ('bot')->__('Email'),
		    'index'   => 'email',
		));
*/
		$this->addColumn ('number', array(
		    'header'  => Mage::helper ('bot')->__('Number'),
		    'index'   => 'number',
            'width'   => '120px',
		));
		$this->addColumn ('firstname', array(
		    'header'  => Mage::helper ('bot')->__('Firstname'),
		    'index'   => 'firstname',
		));
		$this->addColumn ('lastname', array(
		    'header'  => Mage::helper ('bot')->__('Lastname'),
		    'index'   => 'lastname',
		));
		$this->addColumn ('message', array(
		    'header'   => Mage::helper ('bot')->__('Message'),
		    'index'    => 'message',
            'renderer' => 'bot/adminhtml_widget_grid_column_renderer_longtext',
            'truncate' => 2500,
            'nl2br'    => true,
            'bolder'   => true,
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('bot')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
		));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

