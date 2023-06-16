<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function addBotContactTable ($installer, $model, $comment)
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
        ->addColumn ($table, 'type_id', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Type ID',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'name', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'number', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Number',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_business', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is Business',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_enterprise', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is Enterprise',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_me', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is Me',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_psa', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is PSA',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_user', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is User',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_my_contact', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is My Contact',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_wa_contact', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is WA Contact',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'is_active', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'unsigned' => true,
            'nullable' => false,
            'default'  => true,
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

    $installer->getConnection ()->addKey ($table, 'FK_TOLUCA_BOT_TYPE_ID_NUMBER_COMPOSITE', array ('type_id', 'number'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE);
}

addBotContactTable ($installer, Toluca_Bot_Helper_Data::CONTACT_TABLE, 'Toluca Bot Contact');

$installer->endSetup ();

