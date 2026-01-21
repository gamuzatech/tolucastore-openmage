<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$entities = array(
    'quote_payment',
    'order_payment',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'   => 11,
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::PAYMENT_ATTRIBUTE_DEFERRED_INSTALLMENTS_QTY, $options);
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::PAYMENT_ATTRIBUTE_DEFERRED_INTERVAL_DAYS,    $options);
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::PAYMENT_ATTRIBUTE_DEFERRED_FIRST_DUE_DAYS,   $options);
}

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'   => '12,4',
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::PAYMENT_ATTRIBUTE_DEFERRED_PERCENTAGE_FEES, $options);
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::PAYMENT_ATTRIBUTE_DEFERRED_AMOUNT_FEES,     $options);
}

$installer->endSetup ();

