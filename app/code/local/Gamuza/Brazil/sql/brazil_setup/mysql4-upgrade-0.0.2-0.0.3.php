<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('brazil_setup');
$installer->startSetup ();

$installer->addAttribute ('catalog_product', Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_NCM, array(
    'group'            => Mage::helper ('brazil')->__('Brazil'),
    'label'            => Mage::helper ('brazil')->__('NCM'),
    'note'             => Mage::helper ('brazil')->__('Common Mercosur Nomenclature'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'brazil/catalog_product_attribute_source_ncm',
    'type'             => 'varchar',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => false,
    'filterable_in_search' => false,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CEST, array(
    'group'            => Mage::helper ('brazil')->__('Brazil'),
    'label'            => Mage::helper ('brazil')->__('CEST'),
    'note'             => Mage::helper ('brazil')->__('Tax Replacement Specifying Code'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'brazil/catalog_product_attribute_source_cest',
    'type'             => 'varchar',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => false,
    'filterable_in_search' => false,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->addAttribute ('catalog_product', Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CFOP, array(
    'group'            => Mage::helper ('brazil')->__('Brazil'),
    'label'            => Mage::helper ('brazil')->__('CFOP'),
    'note'             => Mage::helper ('brazil')->__('Fiscal Code for Operations and Benefits'),
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'           => 'brazil/catalog_product_attribute_source_cfop',
    'type'             => 'varchar',
    'input'            => 'select',
    'visible'          => true,
    'required'         => false,
    'user_defined'     => false,
    'searchable'       => false,
    'filterable'       => false,
    'comparable'       => false,
    'visible_on_front' => false,
    'unique'           => false,
    'is_configurable'  => false,
    'sort_order'       => 1000,
    'visible_in_advanced_search' => false,
    'filterable_in_search' => false,
    'used_for_promo_rules' => true,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
));

$installer->endSetup ();

