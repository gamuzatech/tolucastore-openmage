<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_History_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_countTotals = true;

    protected $_isExport = true;

    public $_fieldsTotals = array(
        'open_amount' => 0,
        'reinforce_amount' => 0,
        'bleed_amount' => 0,
        'close_amount' => 0,

        'money_amount' => 0,
        'change_amount' => 0,
        'machine_amount' => 0,

        'pagcripto_amount' => 0,
        'picpay_amount' => 0,
        'openpix_amount' => 0,

        'creditcard_amount' => 0,
        'billet_amount' => 0,
        'banktransfer_amount' => 0,
        'check_amount' => 0,

        'shipping_amount' => 0,
        'total_amount' => 0,
    );

	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('pdvHistoryGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('pdv/history')->getCollection ();

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

    public function getTotals ()
    {
        return $this->helper ('pdv')->getTotals ($this);
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
		$this->addColumn ('cashier_id', array(
		    'header'  => Mage::helper ('pdv')->__('Cashier'),
		    'align'   => 'right',
	        'type'    => 'number',
		    'index'   => 'cashier_id',
            'type'    => 'options',
            'options' => Toluca_PDV_Block_Adminhtml_Operator_Grid::getCashiers (),
		));
		$this->addColumn ('operator_id', array(
		    'header'  => Mage::helper ('pdv')->__('Operator'),
		    'align'   => 'right',
	        'type'    => 'number',
		    'index'   => 'operator_id',
            'type'    => 'options',
            'options' => Toluca_PDV_Block_Adminhtml_Cashier_Grid::getOperators (),
		));

		$this->addColumn ('open_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Open'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'open_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('reinforce_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Reinforce'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'reinforce_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('bleed_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Bleed'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'bleed_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('close_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Close'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'close_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
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

		$this->addColumn ('money_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Money'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'money_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('change_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Money Change'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'change_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('machine_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Machine'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'machine_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));

		$this->addColumn ('pagcripto_amount', array(
		    'header'  => Mage::helper ('pdv')->__('PagCripto'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'pagcripto_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('picpay_amount', array(
		    'header'  => Mage::helper ('pdv')->__('PicPay'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'picpay_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('openpix_amount', array(
		    'header'  => Mage::helper ('pdv')->__('OpenPix'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'openpix_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));

		$this->addColumn ('creditcard_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Credit Card'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'creditcard_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('billet_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Billet'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'billet_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('banktransfer_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Bank Transfer'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'banktransfer_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('check_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Check Money'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'check_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('pix_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Pix'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'pix_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));

		$this->addColumn ('shipping_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Shipping'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'shipping_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
		));
		$this->addColumn ('total_amount', array(
		    'header'  => Mage::helper ('pdv')->__('Total'),
		    'align'   => 'right',
	        'type'    => 'price',
		    'index'   => 'total_amount',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'default' => 0,
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

        $this->addExportType('*/*/exportCsv', Mage::helper('pdv')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('pdv')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}
}

