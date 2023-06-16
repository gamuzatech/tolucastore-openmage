<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('basic_setup');
$installer->startSetup ();

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_BRAND, array(
    'group'            => Mage::helper ('basic')->__('Attribute Options'),
    'label'            => Mage::helper ('basic')->__('Brand'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'eav/entity_attribute_source_table',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => true,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => true,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_COLOR, array(
    'group'            => Mage::helper ('basic')->__('Attribute Options'),
    'label'            => Mage::helper ('basic')->__('Color'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'eav/entity_attribute_source_table',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => true,
    'unique'           => false,
    'is_configurable'  => true,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => true,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_MANUFACTURER, array(
    'group'            => Mage::helper ('basic')->__('Attribute Options'),
    'label'            => Mage::helper ('basic')->__('Manufacturer'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'eav/entity_attribute_source_table',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => true,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => true,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_SIZE, array(
    'group'            => Mage::helper ('basic')->__('Attribute Options'),
    'label'            => Mage::helper ('basic')->__('Size'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'eav/entity_attribute_source_table',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => true,
    'unique'           => false,
    'is_configurable'  => true,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => true,
));

$installer->endSetup ();

