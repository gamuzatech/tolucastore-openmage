<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addBrazilNfceResponseTable ($installer, $model, $description)
{
    $table = $installer->getTable ($model);

    $sqlBlock = <<< SQLBLOCK
CREATE TABLE IF NOT EXISTS {$table}
(
    entity_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (entity_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 COMMENT='{$description}';
SQLBLOCK;

    $installer->run ($sqlBlock);

    $installer->getConnection ()
        ->addColumn ($table, 'nfce_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'NFC-e ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'environment_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Environment ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'process_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Process ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'received_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Received ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'protocol_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Protocol ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'receipt_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Receipt ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'average_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'default'  => -1,
            'nullable' => false,
            'comment'  => 'Average ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'qr_code', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'comment'  => 'QR Code',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'url_key', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'comment'  => 'URL Key',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'application', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Application',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'reason', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Reason',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'key', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Key',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'created_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => false,
            'comment'  => 'Created At',
        ));
/*
    $installer->getConnection ()
        ->addColumn ($table, 'updated_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Updated At'
        ));
*/
    $installer->getConnection ()
        ->addColumn ($table, 'emitted_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => false,
            'comment'  => 'Emitted At',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'received_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => false,
            'comment'  => 'Received At',
        ));
}

addBrazilNfceResponseTable ($installer, Gamuza_Brazil_Helper_Data::NFCE_RESPONSE_TABLE, 'Gamuza Brazil NFC-e Response');

$installer->endSetup ();

