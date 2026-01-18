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

$installer->endSetup ();

