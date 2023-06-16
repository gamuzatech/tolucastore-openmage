<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('bot_setup');
$installer->startSetup ();

/**
 * Order & Quote
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
    $installer->addAttribute ($entity, Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_IS_BOT, $options);
}

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'   => 255,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_BOT_TYPE, $options);
}

$installer->endSetup ();

