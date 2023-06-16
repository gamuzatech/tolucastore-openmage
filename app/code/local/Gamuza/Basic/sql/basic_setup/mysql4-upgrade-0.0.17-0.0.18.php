<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('basic_setup');
$installer->startSetup ();

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CUSTOMER_OF_SUPPLIER, array(
    'group'            => Mage::helper ('basic')->__('Supply Chain'),
    'label'            => Mage::helper ('basic')->__('Supplier'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_customer_supplier',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CUSTOMER_OF_MANUFACTURER, array(
    'group'            => Mage::helper ('basic')->__('Supply Chain'),
    'label'            => Mage::helper ('basic')->__('Manufacturer'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_customer_manufacturer',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => true,
    'sort_order'       => 1100,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CUSTOMER_OF_DISTRIBUTER, array(
    'group'            => Mage::helper ('basic')->__('Supply Chain'),
    'label'            => Mage::helper ('basic')->__('Distributer'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_customer_distributer',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1200,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CUSTOMER_OF_SELLER, array(
    'group'            => Mage::helper ('basic')->__('Supply Chain'),
    'label'            => Mage::helper ('basic')->__('Seller'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_customer_seller',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => true,
    'sort_order'       => 1300,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CUSTOMER_OF_RESELLER, array(
    'group'            => Mage::helper ('basic')->__('Supply Chain'),
    'label'            => Mage::helper ('basic')->__('Reseller'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_customer_reseller',
    'type'             => 'int',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => true,
    'sort_order'       => 1400,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->endSetup ();

