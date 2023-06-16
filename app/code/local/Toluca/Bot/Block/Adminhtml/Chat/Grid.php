<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Chat_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botChatGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('bot/chat')->getCollection ();

        $collection->getSelect ()->joinLeft (
            array ('order' => Mage::getSingleton ('core/resource')->getTableName ('sales/order')),
            'main_table.order_id = order.entity_id',
            array ('increment_id')
        );

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
        $categories = array ();

        foreach (Mage::getModel ('catalog/category')->getCollection ()->addNameToResult () as $category)
        {
            $categories [$category->getId ()] = str_repeat (' - ', intval ($category->getLevel ())) . $category->getName ();
        }

        foreach (Mage::getModel ('catalog/product')->getCollection ()->addAttributeToSelect ('name') as $product)
        {
            $products [$product->getId ()] = $product->getName () . ' ' . $product->getSku ();
        }

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('bot')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
            'filter_index' => 'main_table.entity_id',
		));
		$this->addColumn ('type_id', array(
		    'header' => Mage::helper ('bot')->__('Type'),
		    'index'  => 'type_id',
            'type'   => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_bot_type')->toArray (),
		));
		$this->addColumn ('phone', array(
		    'header'  => Mage::helper ('bot')->__('Phone'),
		    'index'   => 'phone',
		));
		$this->addColumn ('increment_id', array(
		    'header' => Mage::helper ('bot')->__('Order'),
		    'index'  => 'increment_id',
		));
		$this->addColumn ('category_id', array(
		    'header'  => Mage::helper ('bot')->__('Category'),
		    'index'   => 'category_id',
            'type'    => 'options',
            'options' => $categories,
		));
		$this->addColumn ('product_id', array(
		    'header'  => Mage::helper ('bot')->__('Product'),
		    'index'   => 'product_id',
            'type'    => 'options',
            'options' => $products,
		));
		$this->addColumn ('product_options', array(
		    'header'  => Mage::helper ('bot')->__('Options'),
		    'index'   => 'product_options',
		));
		$this->addColumn ('product_comment', array(
		    'header'  => Mage::helper ('bot')->__('Comment'),
		    'index'   => 'product_comment',
		));
		$this->addColumn ('number', array(
		    'header'  => Mage::helper ('bot')->__('Number'),
		    'index'   => 'number',
		));
		$this->addColumn ('firstname', array(
		    'header'  => Mage::helper ('bot')->__('Firstname'),
		    'index'   => 'firstname',
		));
		$this->addColumn ('lastname', array(
		    'header'  => Mage::helper ('bot')->__('Lastname'),
		    'index'   => 'lastname',
		));
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('bot')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_chat_status')->toArray (),
            'filter_index' => 'main_table.status',
		));
/*
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('bot')->__('Message'),
		    'index'   => 'message',
		));
*/
		$this->addColumn ('is_muted', array(
		    'header'  => Mage::helper ('bot')->__('Is Muted'),
		    'index'   => 'is_muted',
	        'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('is_notified', array(
		    'header'  => Mage::helper ('bot')->__('Is Notified'),
		    'index'   => 'is_notified',
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
            'getter'   => 'getId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('bot')->__('History'),
                    'field'   => 'id',
                    'url'     => array(
                        'base'   => '*/*/history',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                )
            ),
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('bot')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bot')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

