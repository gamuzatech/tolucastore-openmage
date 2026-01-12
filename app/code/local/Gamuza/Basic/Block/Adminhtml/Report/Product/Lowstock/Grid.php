<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml low stock products report grid block
 */
class Gamuza_Basic_Block_Adminhtml_Report_Product_Lowstock_Grid
    extends Mage_Adminhtml_Block_Report_Product_Lowstock_Grid
{
    protected $_isExport = true;

    protected function _prepareCollection ()
    {
        $result = parent::_prepareCollection ();

        $collection = $this->getCollection ();

        $collection->joinInventoryItem ('notify_stock_qty');

        $this->setCollection ($collection);

        return $result;
    }

    protected function _prepareColumns ()
    {
        $this->addColumn ('entity_id', array(
            'header'    => Mage::helper ('catalog')->__('ID'),
            'sortable'  => false,
            'index'     => 'entity_id',
            'type'      => 'number',
        ));

        $result = parent::_prepareColumns ();

        $this->addColumn ('notify_stock_qty', array(
            'header'    => Mage::helper ('catalog')->__('Notify for Quantity Below'),
            'width'     => '215px',
            'sortable'  => false,
            'filter'    => 'adminhtml/widget_grid_column_filter_range',
            'index'     => 'notify_stock_qty',
            'type'      => 'number',
        ));

        return $result;
    }

    /**
     * @param Mage_Catalog_Model_Product $row
     * @return string
     */
    public function getRowUrl ($row)
    {
        $result = $this->getUrl ('adminhtml/catalog_product/edit', array(
            'store' => $this->getRequest ()->getParam ('store'),
            'id' => $row->getId (),
            'tab' => 'product_info_tabs_inventory',
        ));

        return $result;
    }
}


