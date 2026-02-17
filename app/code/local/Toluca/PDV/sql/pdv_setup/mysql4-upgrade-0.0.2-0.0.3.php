<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Toluca_PDV_Model_Resource_Setup ('pdv_setup');
$installer->startSetup ();

/**
 * Quote & Order
 */
$entities = array(
    'quote',
    'order',
);

/*
if (Mage::helper ('core')->isModuleEnabled ('Gamuza_Brazil'))
{
    $entities [] = 'nfce';
}
*/

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'unsigned' => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_COMANDA_ALERT, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_COMANDA_BILL, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_SUPER_MODE, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV, $options);
}

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'   => 11,
    'unsigned' => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CASHIER_ID,  $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_OPERATOR_ID, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_HISTORY_ID,  $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_SEQUENCE_ID, $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_TABLE_ID,    $options);
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CARD_ID,     $options);
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
    $installer->addAttribute ($entity, Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_EMAIL, $options);
}

$installer->endSetup ();

