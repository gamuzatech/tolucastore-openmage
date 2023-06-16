<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

function updateCatalogCategoryTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, 'sku', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 64,
            'nullable' => true,
            'comment'  => 'SKU',
        ))
    ;
}

updateCatalogCategoryTable ($installer, 'catalog_category_entity', 'Mage Catalog Category Table');

$installer->addAttribute ('catalog_category', Gamuza_Basic_Helper_Data::CATEGORY_ATTRIBUTE_INCLUDE_IN_HOME, array(
    'type'             => 'int',
    'source'           => 'eav/entity_attribute_source_boolean',
    'label'            => Mage::helper ('basic')->__('Include In Home'),
    'input'            => 'select',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'          => true,
    'required'         => true,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => false,
    'group'            => Mage::helper ('basic')->__('General Information'),
    'sort_order'       => 1000,
));

$installer->addAttribute ('catalog_category', Gamuza_Basic_Helper_Data::CATEGORY_ATTRIBUTE_SKU, array(
    'type'             => 'static',
    'backend'          => 'basic/catalog_category_attribute_backend_sku',
    'label'            => Mage::helper ('basic')->__('SKU'),
    'input'            => 'label',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => true,
    'group'            => Mage::helper ('basic')->__('General Information'),
    'sort_order'       => 2000,
));

$installer->addAttribute ('catalog_category', Gamuza_Basic_Helper_Data::CATEGORY_ATTRIBUTE_CODE, array(
    'type'             => 'varchar',
    'label'            => Mage::helper ('basic')->__('Code'),
    'input'            => 'text',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => true,
    'group'            => Mage::helper ('basic')->__('ERP'),
    'sort_order'       => 1000,
));

$installer->endSetup ();

