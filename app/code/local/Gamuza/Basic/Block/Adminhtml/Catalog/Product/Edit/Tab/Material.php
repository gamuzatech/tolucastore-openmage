<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml product raw material grid block
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tab_Material
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('productRawMaterialGrid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('in_products' => 1));
    }

    /**
     * Add filter
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() === 'in_products')
        {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds))
            {
                $productIds = 0;
            }

            if ($column->getFilter()->getValue())
            {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            }
            elseif ($productIds)
            {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
            }
        }
        else
        {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product_link')->useMaterialLinks()
            ->getProductCollection()
            ->setProduct(Mage::registry('current_product'))
            ->addAttributeToSelect(array('name', 'sku', 'price'))
            ->addStoreFilter($this->getRequest()->getParam('store'))
            ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner')
            ->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner')
            ->addFieldToFilter('type_id', array('eq' => Gamuza_Basic_Model_Catalog_Product_Type_Material::TYPE_MATERIAL))
        ;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_products', array(
            'header_css_class'  => 'a-center',
            'type'              => 'checkbox',
            'name'              => 'in_products',
            'values'            => $this->_getSelectedProducts(),
            'align'             => 'center',
            'index'             => 'entity_id',
        ));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'index'     => 'entity_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name',
        ));

        /*
        $this->addColumn('type', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'width'     => 100,
            'index'     => 'type_id',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
        */

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name', array(
            'header'    => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width'     => 130,
            'index'     => 'attribute_set_id',
            'type'      => 'options',
            'options'   => $sets,
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 90,
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));

        $this->addColumn('visibility', array(
            'header'    => Mage::helper('catalog')->__('Visibility'),
            'width'     => 90,
            'index'     => 'visibility',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku',
        ));

        $this->addColumn('price', array(
            'type'      => 'currency',
            'currency_code' => Mage_Directory_Helper_Data::getConfigCurrencyBase(),
        ));

        $this->addColumn('qty', array(
            'header'    => Mage::helper('catalog')->__('Qty'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'qty',
            'editable'  => true,
        ));

        $this->addColumn('action', array(
            'type'   => 'action',
            'getter' => 'getId',
            'index'  => 'stores',
            'actions' => array(
                array(
                    'caption' => Mage::helper('catalog')->__('Edit'),
                    'id'      => 'editlink',
                    'field'   => 'id',
                    'onclick' => "popWin(this.href,'win','width=1000,height=700,resizable=1,scrollbars=1');return false;",
                    'url'     => array(
                        'base'   => 'adminhtml/catalog_product/edit',
                        'params' => array(
                            'store' => $this->getRequest()->getParam('store'),
                            'popup' => 1,
                        ),
                    ),
                ),
            ),
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        // nothing
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/materialGrid', array('_current' => true));
    }

    /**
     * Retrieve selected raw material products
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getProductsMaterial();

        if (!is_array($products))
        {
            $products = array_keys($this->getSelectedMaterialProducts());
        }

        return $products;
    }

    /**
     * Retrieve raw products
     *
     * @return array
     */
    public function getSelectedMaterialProducts()
    {
        $products = array();

        foreach (Mage::registry('current_product')->getMaterialProducts() as $product)
        {
            $products[$product->getId()] = array('qty' => $product->getQty());
        }

        return $products;
    }
}

