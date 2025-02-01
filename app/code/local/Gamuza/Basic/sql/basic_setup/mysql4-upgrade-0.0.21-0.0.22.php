<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

/**
 * Create product links structure (material)
*/
$connection = $installer->getConnection ();

$productLinkTypeTable = $installer->getTable('catalog/product_link_type');
$productLinkTypeData  = array(
    'link_type_id' => Gamuza_Basic_Model_Catalog_Product_Link::LINK_TYPE_MATERIAL,
    'code'         => Gamuza_Basic_Model_Catalog_Product_Type_Material::TYPE_MATERIAL,
);

$connection->insertOnDuplicate ($productLinkTypeTable, $productLinkTypeData, array_keys ($productLinkTypeData));

$productLinkAttributeTable = $installer->getTable('catalog/product_link_attribute');
$productLinkAttributeData  = array(
    'product_link_attribute_id'   => Gamuza_Basic_Model_Catalog_Product_Link::LINK_TYPE_MATERIAL,
    'link_type_id'                => Gamuza_Basic_Model_Catalog_Product_Link::LINK_TYPE_MATERIAL,
    'product_link_attribute_code' => 'qty',
    'data_type'                   => 'int',
);

$connection->insertOnDuplicate ($productLinkAttributeTable, $productLinkAttributeData, array_keys ($productLinkAttributeData));

$installer->endSetup ();

