<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Shipping_Rate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId ('shippingRatesGrid');
        $this->setDefaultSort ('rate_id');
        $this->setDefaultDir ('DESC');
        $this->setSaveParametersInSession (true);
    }

    protected function _prepareCollection ()
    {
        $collection = Mage::getResourceModel ('basic/sales_quote_address_rate_collection');

        $collection->getSelect ()
            ->join(
                array ('sfqa' => Mage::getSingleton ('core/resource')->getTablename ('sales/quote_address')),
                'sfqa.address_id = main_table.address_id',
                array(
                    'sfqa.email',
                    'sfqa.postcode',
                )
            )
            ->join(
                array ('sfq' => Mage::getSingleton ('core/resource')->getTablename ('sales_flat_quote')),
                'sfq.entity_id = sfqa.quote_id',
                array(
                    'quote_id' => 'sfq.entity_id',
                    'sfq.customer_email',
                )
            )
            ->joinLeft(
                array ('sfqi' => 'sales_flat_quote_item'),
                'sfqi.quote_id = sfq.entity_id',
                array ()
            )
            ->group ('rate_id')
            ->columns (array (
                "GROUP_CONCAT(sku SEPARATOR ' ') AS skus",
                "GROUP_CONCAT(qty SEPARATOR ' ') AS qtys"
            ))
        ;

        $this->setCollection ($collection);

        return parent::_prepareCollection ();
    }

    protected function _prepareColumns ()
    {
        $store = $this->_getStore ();

        $this->addColumn ('rate_id', array(
            'header' => Mage::helper ('basic')->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'rate_id',
        ));
        $this->addColumn('address_id', array(
            'header' => Mage::helper('basic')->__('Address ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'address_id',
            'filter_index' => 'sfqa.address_id',
        ));
        $this->addColumn('quote_id', array(
            'header' => Mage::helper('basic')->__('Quote ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'quote_id',
            'filter_index' => 'sfq.entity_id',
        ));
        $this->addColumn ('skus', array(
            'header' => Mage::helper ('basic')->__('SKUs'),
            'index'  => 'skus',
            'filter_index' => 'sfqi.sku',
        ));
        $this->addColumn ('qtys', array(
            'header' => Mage::helper ('basic')->__('Qtys'),
            'index'  => 'qtys',
            'filter_index' => 'sfqi.qty',
        ));
        $this->addColumn ('customer_email', array(
            'header' => Mage::helper ('basic')->__('Customer E-mail'),
            'index'  => 'customer_email',
        ));
        $this->addColumn ('postcode', array(
            'header' => Mage::helper ('basic')->__('Postcode'),
            'index'  => 'postcode',
        ));
/*
        $this->addColumn ('code', array(
            'header' => Mage::helper ('basic')->__('Code'),
            'index'  => 'code',
        ));
        $this->addColumn ('carrier', array(
            'header' => Mage::helper ('basic')->__('Carrier'),
            'index'  => 'carrier',
        ));
*/
        $this->addColumn ('carrier_title', array(
            'header' => Mage::helper ('basic')->__('Carrier Title'),
            'index'  => 'carrier_title',
        ));
/*
        $this->addColumn ('method', array(
            'header' => Mage::helper ('basic')->__('Method Code'),
            'index'  => 'method',
        ));
        $this->addColumn('method_description', array(
            'header'  => Mage::helper('basic')->__('Method Description'),
            'index'   => 'method_description',
        ));
*/
        $this->addColumn ('method_title', array(
            'header'  => Mage::helper ('basic')->__('Method Title'),
            'index'   => 'method_title',
        ));
        $this->addColumn ('price', array(
            'header'  => Mage::helper ('basic')->__('Price'),
            'index'   => 'price',
            'type'    => 'price',
            'filter_index' => 'main_table.price',
            'currency_code' => $store->getBaseCurrency ()->getCode (),
        ));
        $this->addColumn ('error_message', array(
            'header' => Mage::helper ('basic')->__('Error Message'),
            'index'  => 'error_message',
        ));
/*
        $this->addColumn ('created_at', array(
            'header' => Mage::helper ('basic')->__('Created At'),
            'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '100px',
            'filter_index' => 'main_table.created_at',
        ));
*/
        $this->addColumn ('updated_at', array(
            'header' => Mage::helper ('basic')->__('Updated At'),
            'index'  => 'updated_at',
            'type'   => 'datetime',
            'width'  => '100px',
            'filter_index' => 'main_table.updated_at',
        ));

        $this->addExportType ('*/*/exportCsv', Mage::helper ('basic')->__('CSV'));
        $this->addExportType ('*/*/exportExcel', Mage::helper ('basic')->__('Excel XML'));

        return parent::_prepareColumns ();
    }

    public function getRowUrl ($row)
    {
        // nothing
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest ()->getParam ('store', 0);

        return Mage::app ()->getStore ($storeId);
    }
}

