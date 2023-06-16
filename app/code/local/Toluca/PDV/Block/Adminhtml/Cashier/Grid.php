<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Cashier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('pdvCashierGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('pdv/cashier')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

	protected function _prepareColumns ()
	{
        $store = $this->_getStore();

		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('pdv')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
		));
		$this->addColumn ('code', array(
		    'header'  => Mage::helper ('pdv')->__('Code'),
		    'index'   => 'code',
		));
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('pdv')->__('Name'),
		    'index'   => 'name',
		));
		$this->addColumn ('is_active', array(
		    'header'  => Mage::helper ('pdv')->__('Is Active'),
		    'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$this->addColumn ('status', array(
		    'header'  => Mage::helper ('pdv')->__('Status'),
		    'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getModel ('pdv/adminhtml_system_config_source_cashier_status')->toArray (),
		));

		$this->addColumn ('opened_at', array(
			'header' => Mage::helper ('pdv')->__('Opened At'),
			'index'  => 'opened_at',
            'type'   => 'datetime',
		));
		$this->addColumn ('closed_at', array(
			'header' => Mage::helper ('pdv')->__('Closed At'),
			'index'  => 'closed_at',
            'type'   => 'datetime',
		));

		$this->addColumn ('operator_id', array(
		    'header'  => Mage::helper ('pdv')->__('Operator'),
		    'align'   => 'right',
	        'type'    => 'number',
		    'index'   => 'operator_id',
            'type'    => 'options',
            'options' => self::getOperators (),
		));
		$this->addColumn ('history_id', array(
		    'header'  => Mage::helper ('pdv')->__('History ID'),
		    'align'   => 'right',
	        'type'    => 'number',
		    'index'   => 'history_id',
		));
		$this->addColumn ('sequence_id', array(
		    'header'  => Mage::helper ('pdv')->__('Sequence ID'),
		    'align'   => 'right',
	        'type'    => 'number',
		    'index'   => 'sequence_id',
		));
		$this->addColumn ('customer_id', array(
		    'header' => Mage::helper ('pdv')->__('Customer ID'),
		    'index'  => 'customer_id',
            'type'   => 'number',
		));
		$this->addColumn ('quote_id', array(
		    'header' => Mage::helper ('pdv')->__('Quote ID'),
		    'index'  => 'quote_id',
            'type'   => 'number',
		));

		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('pdv')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('pdv')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
		));

        $this->addColumn ('action', array(
            'header'   => Mage::helper ('pdv')->__('Action'),
            'width'    => '50px',
            'type'     => 'action',
            'getter'   => 'getId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('pdv')->__('Edit'),
                    'field'   => 'id',
                    'url'     => array(
                        'base'   => '*/*/edit',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                )
            ),
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('pdv')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('pdv')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}

    public static function getOperators ()
    {
        $result = array ();

        foreach (Mage::getModel ('pdv/operator')->getCollection () as $operator)
        {
            $result [$operator->getId ()] = sprintf ('%s - %s', $operator->getId (), $operator->getName ());
        }

        return $result;
    }
}

