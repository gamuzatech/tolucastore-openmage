<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product attributes grid
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Attribute_Option_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_entityTypeId = null;

    public function __construct ()
    {
        parent::__construct ();
        $this->setId ('productAttributeOptionGrid');
        $this->setDefaultSort ('attribute_code');
        $this->setDefaultDir ('ASC');
        $this->setSaveParametersInSession (true);

        $this->_entityTypeId = Mage::getSingleton ('eav/config')->getEntityType (Mage_Catalog_Model_Product::ENTITY)->getId ();
    }

    protected function _prepareCollection ()
    {
        $collection = Mage::getModel ('basic/eav_entity_attribute_option')->getCollection ();

        $collection->getSelect ()
            ->join (
                array ('ea' => Mage::getSingleton ('core/resource')->getTableName ('eav_attribute')),
                "main_table.attribute_id = ea.attribute_id AND ea.entity_type_id = {$this->_entityTypeId}",
                array ('attribute_code', 'frontend_label')
            )
            ->joinLeft (
                array ('eaov' => Mage::getSingleton ('core/resource')->getTableName ('eav_attribute_option_value')),
                'main_table.option_id = eaov.option_id',
                array ('store_id', 'value_id', 'value')
            )
            ->order ('attribute_code ASC')
            ->order ('store_id ASC')
            ->order ('sort_order ASC')
            ->order ('value ASC')
        ;

        $this->setCollection ($collection);

        return parent::_prepareCollection ();
    }

    protected function _prepareColumns ()
    {
        parent::_prepareColumns ();

        $this->addColumn ('entity_id', array(
            'header' => Mage::helper ('basic')->__('Option ID'),
            'width'  => '100px',
            'type'   => 'number',
            'index'  => 'option_id',
            'filter_index' => 'main_table.option_id',
        ));
        $this->addColumn ('attribute_id', array(
            'header'  => Mage::helper ('basic')->__('Attribute ID'),
            'type'    => 'number',
            'index'   => 'attribute_id',
            'filter_index' => 'main_table.attribute_id',
        ));
        $this->addColumn ('attribute_code', array(
            'header'  => Mage::helper ('basic')->__('Attribute Code'),
            'index'   => 'attribute_code',
        ));
        $this->addColumn ('frontend_label', array(
            'header'  => Mage::helper ('basic')->__('Frontend Label'),
            'index'   => 'frontend_label',
        ));
/*
        $this->addColumn ('store_id', array(
            'header'    => Mage::helper ('basic')->__('Store'),
            'index'     => 'store_id',
            'type'      => 'options',
            'options' => Mage::getSingleton ('adminhtml/system_store')->getStoreOptionHash (true),
            'store_view'=> true,
            'display_deleted' => true,
            'escape' => true,
        ));
*/
        $this->addColumn ('sort_order', array(
            'header' => Mage::helper ('basic')->__('Sort Order'),
            'width'  => '100px',
            'type'   => 'number',
            'index'  => 'sort_order',
        ));
        $this->addColumn ('value_id', array(
            'header'    => Mage::helper ('basic')->__('Value ID'),
            'index'     => 'value_id',
            'type'      => 'number',
        ));
        $this->addColumn ('value', array(
            'header' => Mage::helper ('basic')->__('Value'),
            'index'  => 'value',
        ));

        $this->addColumn ('action', array(
            'header'   => Mage::helper ('basic')->__('Action'),
            'width'    => '50px',
            'type'     => 'action',
            'getter'   => 'getId',
            'index'    => 'stores',
            'filter'   => false,
            'sortable' => false,
            'actions'  => array(
                array(
                    'caption' => Mage::helper ('basic')->__('Edit'),
                    'field'   => 'id',
                    'url'     => array(
                        'base'   => '*/*/edit',
                        'params' => array ('store' => $this->getRequest ()->getParam ('store'))
                    ),
                )
            ),
        ));

        return $this;
    }

    public function getRowUrl ($row)
    {
        // nothing here
    }
}

