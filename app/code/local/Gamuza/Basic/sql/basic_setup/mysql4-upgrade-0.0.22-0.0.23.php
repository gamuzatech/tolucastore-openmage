<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

/**
 * Balance Weight
 */
$installer->addAttribute ('catalog_product', Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_BALANCE_WEIGHT, array(
    'group'            => Mage::helper ('basic')->__('General'),
    'label'            => Mage::helper ('basic')->__('Balance Weight'),
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
 * Quote & Order
 */
$entities = array(
    'quote',
    'order',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'usigned'  => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_WEIGHTED, $options);
}

/**
 * OrderItem & QuoteItem
 */
$entities = array(
    'quote_item',
    'order_item',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'   => '12,4',
    'nullable' => true,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_CUSTOM_WEIGHT, $options);
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_ORIGINAL_BASE_PRICE, $options);
}

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'length'   => 255,
    'nullable' => true,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_UNIQUE_ID, $options);
}

$installer->endSetup ();

