<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

function updateAdminUserTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, 'is_system', array(
            'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is System',
        ))
    ;
}

updateAdminUserTable ($installer, 'admin_user', 'Admin User Table');

function updateApiUserTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, 'is_system', array(
            'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Is System',
        ))
    ;
}

updateApiUserTable ($installer, 'api_user', 'API User Table');

Gamuza_Basic_Model_Install_Installer::applyAllSecurityUpdates ();

$installer->endSetup ();

