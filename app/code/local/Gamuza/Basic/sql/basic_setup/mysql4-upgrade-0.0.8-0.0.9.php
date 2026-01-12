<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('basic_setup');
$installer->startSetup ();

/**
 * Product GTIN
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_GTIN, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('GTIN'),
    'note'             => Mage::helper ('basic')->__('Global Trade Item Number'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'type'             => 'varchar',
    'input'            => 'text',
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
    'used_for_sort_by' => false,
));

/**
 * Product Free Shipping
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_FREE_SHIPPING, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('Free Shipping'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_boolean',
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

/**
 * Product Offer Type
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_OFFER_TYPE, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('Offer Type'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/adminhtml_system_config_source_offer_type',
    'type'             => 'varchar',
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
    'used_for_sort_by' => false,
));

/**
 * Product Code
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_CODE, array(
    'group'            => Mage::helper ('basic')->__('ERP'),
    'label'            => Mage::helper ('basic')->__('Code'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'type'             => 'varchar',
    'input'            => 'text',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => true,
    'filterable'       => true,
    'comparable'       => true,
    'visible_on_front' => true,
    'unique'           => true,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => true,
    'filterable_in_search' => true,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

/**
 * Product Printer ID
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRINTER_ID, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('Printer'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_product_printerId',
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

/**
 * Product Printing
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRINTING, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('Printing'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'basic/eav_entity_attribute_source_product_printing',
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

$installer->endSetup ();

