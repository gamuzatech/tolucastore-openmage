<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Queue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('botQueueGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('bot/queue')->getCollection ();

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
		$this->addColumn ('promotion_id', array(
		    'header' => Mage::helper ('bot')->__('Promotion ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'promotion_id',
		));
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('bot')->__('Name'),
		    'index'   => 'name',
		));
/*
		$this->addColumn ('filename', array(
		    'header'   => Mage::helper ('bot')->__('Filename'),
		    'index'    => 'filename',
            'renderer' => 'bot/adminhtml_widget_grid_column_renderer_link',
            'media'    => 'bot/promotion',
		));
		$this->addColumn ('message', array(
		    'header'  => Mage::helper ('bot')->__('Message'),
		    'index'   => 'message',
            'renderer' => 'bot/adminhtml_widget_grid_column_renderer_longtext',
            'truncate' => 2500,
            'nl2br'    => true,
		));
*/
		$this->addColumn ('contacts_total', array(
		    'header' => Mage::helper ('bot')->__('Contacts Total'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'contacts_total',
		));
		$this->addColumn ('contacts_sent', array(
		    'header' => Mage::helper ('bot')->__('Contacts Sent'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'contacts_sent',
		));
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('bot')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('bot/adminhtml_system_config_source_queue_status')->toArray (),
		));
		$this->addColumn ('started_at', array(
			'header' => Mage::helper ('bot')->__('Started At'),
			'index'  => 'started_at',
            'type'   => 'datetime',
		));
		$this->addColumn ('finished_at', array(
			'header' => Mage::helper ('bot')->__('Finished At'),
			'index'  => 'finished_at',
            'type'   => 'datetime',
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

        $this->addColumn ('promotion', array(
            // 'header'   => Mage::helper ('bot')->__('Action'),
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

        $this->addColumn ('history', array(
            // 'header'   => Mage::helper ('bot')->__('Action'),
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
                        'base'   => '*/adminhtml_queue/history',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                ),
            ),
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('bot')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bot')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

    protected function _prepareMassaction ()
    {
        $this->setMassactionIdField ('entity_id');
        $this->getMassactionBlock ()->setFormFieldName ('queue');

        $statuses = array(
            Toluca_Bot_Helper_Data::QUEUE_STATUS_CANCELED => Mage::helper ('bot')->__('Canceled'),
            Toluca_Bot_Helper_Data::QUEUE_STATUS_STOPPED  => Mage::helper ('bot')->__('Stopped'),
        );

        $this->getMassactionBlock ()->addItem ('status', array(
            'label' => Mage::helper ('adminhtml')->__('Change status'),
            'url'   => $this->getUrl ('*/*/massStatus', array ('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper ('adminhtml')->__('Status'),
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

