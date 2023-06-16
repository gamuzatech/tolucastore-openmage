<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$installer->updateAttribute (Mage_Catalog_Model_Product::ENTITY, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRICE_TYPE, 'frontend_input', 'select');
$installer->updateAttribute (Mage_Catalog_Model_Product::ENTITY, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRICE_TYPE, 'frontend_label', 'Price Type');
$installer->updateAttribute (Mage_Catalog_Model_Product::ENTITY, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRICE_TYPE, 'is_visible',     '1');
$installer->updateAttribute (Mage_Catalog_Model_Product::ENTITY, Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRICE_TYPE, 'source_model',   'basic/bundle_product_attribute_source_price_type');

$installer->endSetup ();

