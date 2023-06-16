<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addPDVHistoryTable ($installer, $model, $comment)
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
        ->addColumn ($table, 'cashier_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Cashier ID',
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
        ->addColumn ($table, 'open_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Open Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'reinforce_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Reinforce Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'bleed_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => false,
            'nullable' => false,
            'comment'  => 'Bleed Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'close_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Close Amount',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'opened_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => false,
            'comment'  => 'Opened At',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'closed_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Closed At',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'money_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Money Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'change_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => false,
            'nullable' => false,
            'comment'  => 'Change Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'machine_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Machine Amount',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'pagcripto_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'PagCripto Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'picpay_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'PicPay Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'openpix_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'OpenPix Amount',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'creditcard_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Credit Card Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'billet_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Billet Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'banktransfer_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Bank Transfer Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'check_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Check Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'pix_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Pix Amount',
        ));

    $installer->getConnection ()
        ->addColumn ($table, 'shipping_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Shipping Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'total_amount', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
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

addPDVHistoryTable ($installer, Toluca_PDV_Helper_Data::HISTORY_TABLE, 'Toluca PDV History');

$installer->endSetup ();

