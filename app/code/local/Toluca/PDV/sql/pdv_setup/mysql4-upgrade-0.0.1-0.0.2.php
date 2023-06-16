<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addPDVOperatorTable ($installer, $model, $comment)
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
        ->addColumn ($table, 'code', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Code',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'password', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 512,
            'nullable' => false,
            'comment'  => 'Password',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_active', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is Active',
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

addPDVOperatorTable ($installer, Toluca_PDV_Helper_Data::OPERATOR_TABLE, 'Toluca PDV Operator');

$installer->endSetup ();

