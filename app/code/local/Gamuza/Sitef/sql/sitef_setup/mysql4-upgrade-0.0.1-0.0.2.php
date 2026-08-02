<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('sitef_setup');
$installer->startSetup ();

$entities = array(
    'quote',
    'order',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'visible'  => true,
    'required' => false
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Sitef_Helper_Data::ORDER_ATTRIBUTE_IS_SITEF, $options);
}

$installer->endSetup ();

