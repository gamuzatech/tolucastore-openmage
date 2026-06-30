<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addPinpadTransactionsTable ($installer, $model, $description)
{
    $table = $installer->getTable ($model);

    $sqlBlock = <<< SQLBLOCK
CREATE TABLE IF NOT EXISTS {$table}
(
    entity_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='{$description}';
SQLBLOCK;

    $installer->run ($sqlBlock);

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
        ->addColumn ($table, 'operator_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Operator ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'merchant_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Merchant ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'terminal_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Terminal ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => false,
            'nullable' => false,
            'comment'  => 'Payment ID',
            'default'  => -1,
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_confirmation', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => false,
            'nullable' => false,
            'comment'  => 'Payment Confirmation',
            'default'  => -1,
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Payment Amount',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'authorization_data', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Authorization Data',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'transaction_data', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Transaction Data',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'function_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Function ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_type', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Payment Type',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Payment Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_description', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Payment Description',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'transaction_datetime', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Transaction Datetime',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'receipt_type', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Receipt Type',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'authorizer_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'authorizer_id',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_brand', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'card_brand',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'sitef_nsu', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Sitef NSU',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'host_nsu', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Host NSU',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'authorization_code', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Authorization Code',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_bin', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Bin',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'institution_name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Institution Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'establishment_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Establishment ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'authorizer_network_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Authorizer Network ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'payment_sequence', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Payment Sequence',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'gerpdv_data', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'GerPdv Data',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'nfce_card_brand', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'NFC-e Card Brand',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'nfce_authorization_number', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'NFC-e Authorization Number',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_expiration_date', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Expiration Date',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_holder_name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Holder Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_last_digits', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Last Digits',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'authorization_response_code', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Authorization Response Code',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_number', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Number',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_holder_birth_date', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Holder Birth Date',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Card Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_read_type', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Card Read Type',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'card_read_status', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Card Read Status',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'transaction_identifier_nit', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Transaction Identifier NIT',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'debit_bill_payment_supported', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Debit Bill Payment Supported',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'credit_bill_payment_supported', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Credit Bill Payment Supported',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'sensitive_fields_collect', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Sensitive Fields Collect',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'sensitive_fields_begin', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Sensitive Fields Begin',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'sensitive_fields_end', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Sensitive Fields End',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'paper_signature_required', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Paper Signature Required',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'status', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Status'
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'message', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => true,
            'comment'  => 'Message'
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
            'comment'  => 'Updated At'
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'authorized_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Authorized At'
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'canceled_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Canceled At'
        ));
}

addPinpadTransactionsTable ($installer, Gamuza_Sitef_Helper_Data::PINPAD_TRANSACTION_TABLE, 'Gamuza Sitef Pinpad Transaction');

$installer->endSetup ();

