<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup('basic_setup');
$installer->startSetup ();

function addBasicQuoteDraftTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $sqlBlock = <<< SQLBLOCK
CREATE TABLE IF NOT EXISTS {$table}
(
    entity_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='{$comment}';
SQLBLOCK;

    $installer->run ($sqlBlock);

    $installer->getConnection ()
        ->addColumn ($table, 'state', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'State',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'increment_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Increment ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'quote_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Quote ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'order_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Order ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'order_increment_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Order Increment ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'store_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Store ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'customer_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Customer ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_method', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Payment Method',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'shipping_method', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Shipping Method',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'shipping_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'nullable' => false,
            'comment'  => 'Shipping Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'subtotal_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'nullable' => false,
            'comment'  => 'Subtotal Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'total_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'nullable' => false,
            'comment'  => 'Total Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'created_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => false,
            'comment'  => 'Created At',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'updated_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Updated At',
        ));
}

addBasicQuoteDraftTable ($installer, Gamuza_Basic_Helper_Data::BASIC_ORDER_DRAFT_TABLE, 'Gamuza Basic Order Draft Table');

$installer->addEntityType('quote_draft', array(
    'entity_model' => 'basic/quote_draft',
    'table'        => 'basic/quote_draft',
    'increment_model'      => 'eav/entity_increment_numeric',
    'increment_per_store'  => 1,
    'increment_pad_length' => 8,
    'increment_pad_char'   => 0,
));

/**
 * Quote & Order
 */
$entities = array(
    'quote',
    'order',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'unsigned' => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::QUOTE_ATTRIBUTE_IS_DRAFT, $options);
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
    $installer->addAttribute ($entity, Gamuza_Basic_Helper_Data::QUOTE_ATTRIBUTE_DRAFT_ID, $options);
}

$installer->endSetup ();

