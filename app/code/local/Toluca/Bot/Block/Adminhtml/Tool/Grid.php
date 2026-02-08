<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Tool_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botToolGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
        $chat = Mage::registry ('current_chat');

		$collection = Mage::getModel ('bot/tool')->getCollection ()
            ->addFieldToFilter ('chat_id', array ('eq' => $chat->getId ()))
        ;

		$messageId = $this->getRequest ()->getParam ('message_id');

		if (!empty ($messageId))
		{
			$collection->addFieldToFilter ('message_id', array ('eq' => $messageId));
		}

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
		$this->addColumn ('message_id', array(
		    'header' => Mage::helper ('bot')->__('Message ID'),
		    'align'  => 'right',
		    'index'  => 'message_id',
            'type'   => 'number',
		));
		$this->addColumn ('bot_type', array(
		    'header' => Mage::helper ('bot')->__('Bot'),
		    'index'  => 'bot_type',
            'type'   => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_bot_type')->toArray (),
		));
		$this->addColumn ('type_id', array(
		    'header' => Mage::helper ('bot')->__('Type'),
		    'index'  => 'type_id',
            'type'   => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_tool_type')->toArray (),
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
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('bot')->__('Name'),
		    'index'   => 'name',
            'width'   => '120px',
		));
		$this->addColumn ('path', array(
		    'header'  => Mage::helper ('bot')->__('Path'),
		    'index'   => 'path',
            'width'   => '120px',
		));
		$this->addColumn ('body', array(
			'header'   => Mage::helper ('bot')->__('Body'),
			'index'    => 'body',
			'renderer' => 'bot/adminhtml_widget_grid_column_renderer_longtext',
			'truncate' => 2500,
			'nl2br'    => true,
			'bolder'   => true,
		));
		$this->addColumn ('result', array(
			'header'   => Mage::helper ('bot')->__('Result'),
			'index'    => 'result',
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

        $this->addColumn ('message_history', array(
            'header'   => Mage::helper ('bot')->__('History'),
            'width'    => '50px',
            'type'     => 'action',
            'getter'   => 'getChatId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('bot')->__('Messages'),
                    'field'   => 'chat_id',
                    'url'     => array(
                        'base'   => '*/*/message',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                )
            ),
        ));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

