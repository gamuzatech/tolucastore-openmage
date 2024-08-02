<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Nfe_Response_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('brazilNfeResponseGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
        $nfe = Mage::registry ('current_nfe');

		$collection = Mage::getModel ('brazil/nfe_response')->getCollection ()
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
		$this->addColumn ('qr_code', array(
			'header' => Mage::helper ('brazil')->__('QR Code'),
            'align'  => 'right',
			'index'  => 'qr_code',
            'renderer' => 'brazil/adminhtml_widget_grid_column_renderer_link',
		));
/*
		$this->addColumn ('url_key', array(
			'header' => Mage::helper ('brazil')->__('URL Key'),
            'align'  => 'right',
			'index'  => 'url_key',
            'renderer' => 'brazil/adminhtml_widget_grid_column_renderer_link',
		));
*/
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
		$this->addColumn ('receipt_id', array(
		    'header'  => Mage::helper ('brazil')->__('Receipt'),
		    'align'   => 'right',
		    'index'   => 'receipt_id',
		));
		$this->addColumn ('average_id', array(
		    'header'  => Mage::helper ('brazil')->__('Average'),
		    'align'   => 'right',
            'width'   => '50px',
            'type'    => 'number',
		    'index'   => 'average_id',
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

