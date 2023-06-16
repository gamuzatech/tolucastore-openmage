<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('picpay_setup');
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
    $installer->addAttribute ($entity, Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_PICPAY_STATUS, $options);
    $installer->addAttribute ($entity, Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_PICPAY_URL,    $options);
}

$installer->endSetup ();

