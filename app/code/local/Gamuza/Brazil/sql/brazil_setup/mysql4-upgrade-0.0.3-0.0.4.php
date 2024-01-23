<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('brazil_setup');
$installer->startSetup ();

$entities = array(
    'quote_item',
    'order_item',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'length'   => 255,
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_NCM,  $options);
    $installer->addAttribute ($entity, Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CEST, $options);
    $installer->addAttribute ($entity, Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CFOP, $options);
}

$installer->endSetup ();

