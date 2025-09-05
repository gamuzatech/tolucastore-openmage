<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Print_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_isExport = true;

	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('pdvPrintGrid');
		$this->setDefaultSort ('entity_id');
		$this->setDefaultDir ('DESC');
		$this->setSaveParametersInSession (true);
    }

	protected function _prepareCollection ()
	{
		$collection = Mage::getModel ('pdv/print')->getCollection ();

		$productTypeId = Mage::getModel ('eav/entity')
            ->setType (Mage_Catalog_Model_Product::ENTITY)
            ->getTypeId ()
		;

        $productNameAttribute = Mage::getModel ('eav/entity_attribute')
            ->loadByCode ($productTypeId, 'name')
		;

		$collection->getSelect()
            ->joinLeft(
                array ('sfo' => Mage::getSingleton ('core/resource')->getTableName ('sales/order')),
                'main_table.order_id = sfo.entity_id',
                array(
					'increment_id',
				)
            )
			->joinLeft(
                array ('cpe' => Mage::getSingleton ('core/resource')->getTableName ('catalog/product')),
                'main_table.product_id = cpe.entity_id',
                array(
					'sku',
				)
            )
			->joinLeft(
                array('cpev' => Mage::getSingleton ('core/resource')->getTableName ('catalog_product_entity_' . $productNameAttribute->getBackendType ())),
                sprintf ('main_table.product_id = cpev.entity_id AND cpev.store_id = 0 AND cpev.attribute_id = %d', $productNameAttribute->getAttributeId ()),
                array(
					'name' => 'cpev.value',
				)
            );
		;

		$this->setCollection ($collection);

		return parent::_prepareCollection ();
	}

	protected function _prepareColumns ()
	{
		$this->addColumn ('entity_id', array(
		    'header' => Mage::helper ('pdv')->__('ID'),
		    'align'  => 'right',
	        'type'   => 'number',
		    'index'  => 'entity_id',
			'filter_index' => 'main_table.entity_id',
		));
		$this->addColumn ('type_id', array(
		    'header'  => Mage::helper ('pdv')->__('Type'),
		    'index'   => 'type_id',
            'type'    => 'options',
            'options' => Mage::getModel ('pdv/adminhtml_system_config_source_print_type')->toArray (),
			'filter_index' => 'main_table.type_id',
		));
		$this->addColumn ('customer_id', array(
		    'header'  => Mage::helper ('pdv')->__('Customer'),
		    'index'   => 'customer_id',
            'type'    => 'options',
            'options' => self::getCustomers (),
			'filter_index' => 'main_table.customer_id',
		));
		$this->addColumn ('history_id', array(
		    'header'  => Mage::helper ('pdv')->__('History ID'),
		    'index'   => 'history_id',
            'type'    => 'number',
		));
		$this->addColumn ('sequence_id', array(
		    'header'  => Mage::helper ('pdv')->__('Sequence ID'),
		    'index'   => 'sequence_id',
            'type'    => 'number',
		));
		$this->addColumn ('table_id', array(
		    'header'  => Mage::helper ('pdv')->__('Table ID'),
		    'index'   => 'table_id',
            'type'    => 'number',
		));
		$this->addColumn ('card_id', array(
		    'header'  => Mage::helper ('pdv')->__('Card ID'),
		    'index'   => 'card_id',
            'type'    => 'number',
		));
		$this->addColumn ('cashier_id', array(
		    'header'  => Mage::helper ('pdv')->__('Cashier'),
		    'index'   => 'cashier_id',
			'type'    => 'options',
            'options' => self::getCashiers (),
		));
		$this->addColumn ('operator_id', array(
		    'header'  => Mage::helper ('pdv')->__('Operator'),
		    'index'   => 'operator_id',
			'type'    => 'options',
            'options' => self::getOperators (),
		));
		$this->addColumn ('quote_id', array(
		    'header'  => Mage::helper ('pdv')->__('Cart'),
		    'index'   => 'quote_id',
            'type'    => 'number',
			'filter_index' => 'main_table.quote_id',
		));
		/*
		$this->addColumn ('order_id', array(
		    'header' => Mage::helper ('pdv')->__('Order ID'),
		    'index'  => 'order_id',
			'type'   => 'number',
		));
		*/
		$this->addColumn ('increment_id', array(
		    'header' => Mage::helper ('pdv')->__('Order Inc. ID'),
		    'index'  => 'increment_id',
		));
		$this->addColumn ('item_id', array(
		    'header'  => Mage::helper ('pdv')->__('Item ID'),
		    'index'   => 'item_id',
		    'type'    => 'number',
		));
		/*
		$this->addColumn ('product_id', array(
		    'header'  => Mage::helper ('pdv')->__('Product ID'),
		    'index'   => 'product_id',
            'type'    => 'number',
		));
		*/
		$this->addColumn ('sku', array(
		    'header'  => Mage::helper ('pdv')->__('SKU'),
		    'index'   => 'sku',
			'filter_index' => 'cpe.sku',
		));
		$this->addColumn ('name', array(
		    'header'  => Mage::helper ('pdv')->__('Name'),
		    'index'   => 'name',
			'filter_index' => 'cpev.value',
		));
		$this->addColumn ('qty_printed', array(
		    'header'  => Mage::helper ('pdv')->__('Qty'),
		    'align'   => 'right',
		    'index'   => 'qty_printed',
			'type'    => 'number',
		));
		$this->addColumn ('remote_ip', array(
			'header' => Mage::helper ('pdv')->__('Remote IP'),
			'index'  => 'remote_ip',
			'filter_index' => 'main_table.remote_ip',
		));
		$this->addColumn ('job_id', array(
			'header' => Mage::helper ('pdv')->__('Job ID'),
			'align'   => 'right',
			'index'  => 'job_id',
			'type'    => 'number',
		));
		$this->addColumn ('created_at', array(
			'header' => Mage::helper ('pdv')->__('Created At'),
			'index'  => 'created_at',
            'type'   => 'datetime',
			'filter_index' => 'main_table.created_at',
		));
		$this->addColumn ('updated_at', array(
			'header' => Mage::helper ('pdv')->__('Updated At'),
			'index'  => 'updated_at',
            'type'   => 'datetime',
			'filter_index' => 'main_table.updated_at',
		));
		$this->addColumn ('printed_at', array(
			'header' => Mage::helper ('pdv')->__('Printed At'),
			'index'  => 'printed_at',
            'type'   => 'datetime',
			'filter_index' => 'main_table.printed_at',
		));

        $this->addExportType('*/*/exportCsv', Mage::helper('pdv')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('pdv')->__('Excel XML'));

		return parent::_prepareColumns ();
	}

	public function getRowUrl ($row)
	{
        // nothing here
	}

	public static function getCustomers ()
    {
        $result = array ();

        $collection = Mage::getModel ('customer/customer')->getCollection ()
            ->addNameToSelect()
        ;

        foreach ($collection as $customer)
        {
            $result [$customer->getId ()] = sprintf ('%s - %s', $customer->getId (), $customer->getName ());
        }

        return $result;
    }

	public static function getCashiers ()
    {
        $result = array ();

        $collection = Mage::getModel ('pdv/cashier')->getCollection ();

        foreach ($collection as $cashier)
        {
            $result [$cashier->getId ()] = sprintf ('%s - %s', $cashier->getId (), $cashier->getName ());
        }

        return $result;
    }

	public static function getOperators ()
    {
        $result = array ();

        $collection = Mage::getModel ('pdv/operator')->getCollection ();

        foreach ($collection as $operator)
        {
            $result [$operator->getId ()] = sprintf ('%s - %s', $operator->getId (), $operator->getName ());
        }

        return $result;
    }
}

