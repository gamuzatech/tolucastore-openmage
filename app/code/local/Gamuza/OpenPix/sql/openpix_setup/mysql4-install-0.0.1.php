<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('openpix_setup');
$installer->startSetup ();

$entities = array(
    'quote_payment',
    'order_payment',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_STATUS, $options);
    $installer->addAttribute ($entity, Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_URL,    $options);
    $installer->addAttribute ($entity, Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_TRANSACTION_ID, $options);
    $installer->addAttribute ($entity, Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_CORRELATION_ID, $options);
}

$installer->endSetup ();

