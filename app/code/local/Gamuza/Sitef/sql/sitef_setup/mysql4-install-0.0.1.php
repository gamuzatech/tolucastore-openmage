<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('sitef_setup');
$installer->startSetup ();

$entities = array(
    'quote_payment',
    'order_payment',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'   => 11,
    'unsigned' => true,
    'nullable' => true,
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Sitef_Helper_Data::PAYMENT_ATTRIBUTE_SITEF_TRANS_ID, $options);
}

$installer->endSetup ();

