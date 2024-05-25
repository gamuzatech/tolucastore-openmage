<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$entities = array(
    'quote_item',
    'order_item',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'length'   => 255,
    'nullable' => true,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_GTIN, $options);
}

$installer->endSetup ();

