<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Shipping_Tablerate_Grid
    extends Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid
{
    /**
     * Prepare shipping table rate collection
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var $collection Mage_Shipping_Model_Mysql4_Carrier_Tablerate_Collection */
        $collection = Mage::getResourceModel('shipping/carrier_tablerate_collection');

        $collection->setConditionFilter($this->getConditionName())
            ->setWebsiteFilter($this->getWebsiteId());

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

    /**
     * Prepare table columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $store = $this->_getStore();

        $this->addColumn ('entity_id', array(
            'header' => Mage::helper ('adminhtml')->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'pk',
        ));
        $this->addColumn ('website_id', array(
            'header'  => Mage::helper ('adminhtml')->__('Website'),
            'index'   => 'website_id',
            'type'    => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getWebsiteOptionHash (true),
        ));

        parent::_prepareColumns();

        $this->removeColumn ('dest_country');
        $this->removeColumn ('dest_region');

        $this->addColumnAfter ('dest_country_id', array(
            'header' => Mage::helper('adminhtml')->__('Country'),
            'index'  => 'dest_country_id',
            'type'   => 'country',
        ), 'website_id');
        $this->addColumnAfter ('dest_region_id', array(
            'header'  => Mage::helper('adminhtml')->__('Region'),
            'index'   => 'dest_region_id',
            'type'    => 'options',
            'options' => Mage::getModel('basic/adminhtml_system_config_source_allregion')->toOptionArray(),
        ), 'dest_country_id');
        $this->addColumnAfter('condition_name', array(
            'header'  => Mage::helper('adminhtml')->__('Condition Name'),
            'index'   => 'condition_name',
            'type'    => 'options',
            'options' => Mage::getModel('basic/adminhtml_system_config_source_shipping_tablerate')->toArray(),
        ), 'dest_zip');

        $label = Mage::getSingleton('shipping/carrier_tablerate')
            ->getCode('condition_name_short', $this->getConditionName());
        $this->addColumn('condition_value', array(
            'header' => $label,
            'index'  => 'condition_value',
            'type'   => 'number',
        ));

        $this->addColumnAfter ('price', array(
            'header' => Mage::helper('adminhtml')->__('Shipping Price'),
            'index'  => 'price',
            'type'   => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
        ), 'condition_value');
        $this->addColumnAfter ('cost', array(
            'header' => Mage::helper('adminhtml')->__('Shipping Cost'),
            'index'  => 'cost',
            'type'   => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
        ), 'price');

        $this->sortColumnsByOrder ();

        $this->addExportType('*/*/exportCsv', Mage::helper('basic')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('basic')->__('Excel XML'));
    }

    public function getConditionName()
    {
        return Mage::getStoreConfig('carriers/tablerate/condition_name');
    }
}

