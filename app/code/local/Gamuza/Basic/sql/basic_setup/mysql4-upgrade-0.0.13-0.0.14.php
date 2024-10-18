<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

function updateCoreConfigDataTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

$updateCoreConfigDataQuery = <<< QUERY
    ALTER TABLE {$table} MODIFY COLUMN scope varchar(255) DEFAULT 'default' NOT NULL COMMENT 'Config Scope'
QUERY;

    Mage::getSingleton ('core/resource')
        ->getConnection ('core_write')
        ->query ($updateCoreConfigDataQuery)
    ;
}

function updateCoreSessionTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

$updateCoreSessionQuery = <<< QUERY
    ALTER TABLE {$table} MODIFY session_id VARCHAR(512) NOT NULL COMMENT '{$comment}'
QUERY;

    Mage::getSingleton ('core/resource')
        ->getConnection ('core_write')
        ->query ($updateCoreSessionQuery)
    ;
}

function updateAPISessionTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

$updateAPISessionQuery = <<< QUERY
    ALTER TABLE {$table} MODIFY sessid VARCHAR(512) NOT NULL COMMENT '{$comment}'
QUERY;

    Mage::getSingleton ('core/resource')
        ->getConnection ('core_write')
        ->query ($updateAPISessionQuery)
    ;
}

function updateLogVisitorTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

$updateLogVisitorQuery = <<< QUERY
    ALTER TABLE {$table} MODIFY session_id VARCHAR(512) NOT NULL COMMENT '{$comment}'
QUERY;

    Mage::getSingleton ('core/resource')
        ->getConnection ('core_write')
        ->query ($updateLogVisitorQuery)
    ;
}

updateCoreConfigDataTable ($installer, 'core_config_data', 'Config Data');
updateCoreSessionTable ($installer, 'core_session', 'Session ID');
updateAPISessionTable ($installer, 'api_session', 'Session ID');
updateLogVisitorTable ($installer, 'log_visitor', 'Session ID');

$installer->endSetup ();

