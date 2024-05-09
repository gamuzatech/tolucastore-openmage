<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addBrazilIbptTable ($installer, $model, $description)
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
        ->addColumn ($table, 'code', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Code',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'exception', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => true,
            'comment'  => 'Exception',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'type', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Type',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'description', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 500,
            'nullable' => false,
            'comment'  => 'Description',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'national_federal', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'National Federal',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'imported_federal', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Imported Federal',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'state', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'State',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'local', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length'   => '12,4',
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Local',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'key', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Key',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'version', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Version',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'source', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Source',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'begin_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATE,
            'nullable' => true,
            'comment'  => 'Begin At',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'end_at', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_DATE,
            'nullable' => true,
            'comment'  => 'End At',
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

addBrazilIbptTable ($installer, Gamuza_Brazil_Helper_Data::IBPT_TABLE, 'Gamuza Brazil IBPT');

$installer->endSetup ();

