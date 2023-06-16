<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('pagcripto_setup');
$installer->startSetup ();

$entities = array(
    'sales/quote_payment',
    'sales/order_payment',
);

foreach ($entities as $entity)
{
    $table = $installer->getTable ($entity);

    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_CURRENCY, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'PagCripto Currency',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_ADDRESS, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'PagCripto Address',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_STATUS, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'PagCripto Status',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_CONFIRMATIONS, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'PagCripto Confirmations',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_RECEIVED_AMOUNT, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '16,8',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'PagCripto Received Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_PAYMENT_REQUEST, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'PagCripto Payment Request',
        ));
}

$entities = array(
    'sales/quote_payment',
    'sales/order_payment',
);

foreach ($entities as $entity)
{
    $table = $installer->getTable ($entity);

    $installer->getConnection ()
        ->addColumn ($table, Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_AMOUNT, array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '16,8',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'PagCripto Amount',
        ));
}

$installer->endSetup ();

