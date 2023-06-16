<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Customer_Grid
    extends Mage_Adminhtml_Block_Customer_Grid
{
    protected $_isExport = true;

    protected function _prepareCollection ()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('created_at')
            ->addAttributeToSelect('group_id')
            ->addAttributeToSelect('cellphone')
            ->addAttributeToSelect ('taxvat')
            ->addAttributeToSelect ('dob')
            ->addAttributeToSelect ('gender')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_cellphone', 'customer_address/cellphone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left');

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        $this->removeColumn ('Telephone');
        $this->removeColumn ('billing_country_id');

        $this->addColumnAfter('billing_city', array(
            'header'    => Mage::helper('customer')->__('City'),
            'width'     => '150',
            'index'     => 'billing_city',
        ), 'billing_postcode');

        $this->addColumnAfter('cellphone', array(
            'header'    => Mage::helper('customer')->__('Cellphone'),
            'width'     => '100',
            'index'     => 'cellphone',
        ), 'email');

        $this->addColumnAfter ('taxvat', array(
            'header'    => Mage::helper ('customer')->__('Taxvat'),
            'width'     => '100',
            'index'     => 'taxvat',
        ), 'cellphone');

        $this->addColumnAfter ('dob', array(
            'header'    => Mage::helper ('customer')->__('Date of Birth'),
            'width'     => '100',
            'index'     => 'dob',
            'type'      => 'date',
            'time'      => false,
            'format'    => Mage::app ()->getLocale ()->getDateFormat (Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'filter_index' => 'dob',
            'filter_condition_callback' => array ($this, '_dobFilterConditionCallback'),
        ), 'taxvat');

        $this->addColumnAfter ('gender', array(
            'header'    => Mage::helper ('customer')->__('Gender'),
            'width'     => '100',
            'index'     => 'gender',
            'type'      => 'options',
            'options'   => Mage::getModel ('basic/adminhtml_system_config_source_customer_gender')->toOptionArray (),
        ), 'dob');

        $this->sortColumnsByOrder();

        return $result;
    }

    protected function _dobFilterConditionCallback ($collection, $column)
    {
        $value = $column->getFilter ()->getValue ();

        if (!empty ($value))
        {
            $from = $value ['from'];
            $to   = $value ['to'];

            if (isset ($from) && $from instanceof Zend_Date)
            {
                $this->getCollection ()->addAttributeToFilter ($column->getFilterIndex (), array ('gteq' => $from->get('YYYY-MM-dd')));
            }

            if (isset ($to) && $to instanceof Zend_Date)
            {
                $this->getCollection ()->addAttributeToFilter ($column->getFilterIndex (), array ('lteq' => $to->get('YYYY-MM-dd')));
            }
        }

        return $this;
    }
}

