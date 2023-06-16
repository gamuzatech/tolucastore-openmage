<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('basic_setup');
$installer->startSetup ();

$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_EAN, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('EAN'),
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

$installer->endSetup ();

