<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml sales order create block
 */
class Gamuza_Basic_Block_Adminhtml_Sales_Order_Create_Customer_Grid
    extends Mage_Adminhtml_Block_Sales_Order_Create_Customer_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('cellphone')
            ->addAttributeToSelect('created_at')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_cellphone', 'customer_address/cellphone', 'default_billing', null, 'left')
            ->joinAttribute('billing_regione', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
            ->joinField('store_name', 'core/store', 'name', 'store_id=store_id', null, 'left');

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('Telephone');

        $this->addColumnAfter('cellphone', array(
            'header'    =>Mage::helper('sales')->__('Cellphone'),
            'width'     =>'100px',
            'index'     =>'cellphone'
        ), 'email');

        $this->addColumnAfter('billing_city', array(
            'header' => Mage::helper('sales')->__('City'),
            'index'  => 'billing_city'
        ), 'billing_postcode');

        $this->addColumnAfter('billing_regione', array(
            'header' => Mage::helper('sales')->__('State/Province'),
            'width'  => '100px',
            'index'  => 'billing_regione',
        ), 'billing_city');

        $this->sortColumnsByOrder();

        return $this;
    }
}

