<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml product wishlist grid block
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tab_Wishlist
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('productWishlistGrid');
        $this->setDefaultSort('added_at');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);

        $this->_entityTypeId = Mage::getModel ('eav/entity')->setType (Gamuza_Basic_Model_Customer_Customer::ENTITY)->getTypeId ();

        $this->_firstnameAttribute = Mage::getModel ('eav/entity_attribute')->loadByCode ($this->_entityTypeId, 'firstname');
        $this->_lastnameAttribute  = Mage::getModel ('eav/entity_attribute')->loadByCode ($this->_entityTypeId, 'lastname');
    }

    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('wishlist/item')->getCollection()
            ->addDaysInWishlist()
            ->addWishListSortOrder()
        ;

       $collection->getSelect()
            ->join(
                array('wishlist' => Mage::getSingleton('core/resource')->getTableName('wishlist/wishlist')),
                'main_table.wishlist_id = wishlist.wishlist_id',
                array('customer_id')
            )
            ->join(
                array('firstname' => Mage::getSingleton('core/resource')->getTableName('customer_entity_' . $this->_firstnameAttribute->getBackendType())),
                'wishlist.customer_id = firstname.entity_id AND firstname.attribute_id = ' . $this->_firstnameAttribute->getAttributeId(),
                array('customer_firstname' => 'firstname.value')
            )
            ->join(
                array('lastname' => Mage::getSingleton('core/resource')->getTableName('customer_entity_' . $this->_lastnameAttribute->getBackendType())),
                'wishlist.customer_id = lastname.entity_id AND lastname.attribute_id = ' . $this->_lastnameAttribute->getAttributeId(),
                array('customer_lastname' => 'lastname.value')
            )
            ->columns(array(
                'customer_name' => "CONCAT_WS(' ', firstname.value, lastname.value)",
            ))
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
        if (!Mage::app()->isSingleStoreMode())
        {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('catalog')->__('Store'),
                'index'  => 'store_id',
                'type'   => 'store',
                'store_view' => true
            ));
        }

        $this->addColumn('customer_id', array(
            'header' => Mage::helper('catalog')->__('Customer ID'),
            'type'   => 'number',
            'index'  => 'customer_id',
        ));

        $this->addColumn('customer_name', array(
            'header' => Mage::helper('catalog')->__('Customer'),
            'index'  => 'customer_name',
            'filter_index' => 'customer_name',
            'filter_condition_callback' => array ($this, '_customernameFilterConditionCallback'),
        ));

        $this->addColumn('qty', array(
            'header' => Mage::helper('catalog')->__('Qty'),
            'type'   => 'number',
            'index'  => 'qty',
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('catalog')->__('Description'),
            'index'  => 'description',
        ));

        $this->addColumn('added_at', array(
            'header' => Mage::helper('catalog')->__('Date Added'),
            'index'  => 'added_at',
            'type'   => 'datetime',
        ));

        $this->addColumn(
            'action',
            array(
                'type'    => 'action',
                'getter'  => 'getCustomerId',
                'index'   => 'stores',
                'header'  => Mage::helper('catalog')->__('Customer'),
                'actions' => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'field'   => 'id',
                        'url' => array(
                            'base'   => 'adminhtml/customer/edit',
                            'params' => array('store' => $this->getRequest()->getParam('store'))
                        ),
                    )
                ),
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/customer/edit', array('id' => $row->getCustomerId()));
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/wishlist', array('_current' => true));
    }

    protected function _customernameFilterConditionCallback ($collection, $column)
    {
        $value = $column->getFilter ()->getValue ();

        if (!empty ($value))
        {
            $this->getCollection ()->getSelect ()->where (sprintf ("firstname.value LIKE '%%%s%%' OR lastname.value LIKE '%%%s%%'", $value, $value));
        }

        return $this;
    }
}

