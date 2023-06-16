<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$installer->addAttribute (Mage_Catalog_Model_Product::ENTITY, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_SKU_POSITION, array(
    'type'             => 'static',
    'label'            => Mage::helper ('basic')->__('SKU Position'),
    'input'            => 'label',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => false,
    'group'            => Mage::helper ('basic')->__('General'),
    'sort_order'       => 10000,
    'used_in_product_listing' => true,
));

function updateCatalogProductTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_SKU_POSITION, array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => true,
            'comment'  => 'SKU Position',
        ))
    ;
}

updateCatalogProductTable ($installer, 'catalog_product_entity', null);

$installer->endSetup ();

