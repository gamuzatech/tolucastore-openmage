<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

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

updateCoreSessionTable ($installer, 'core_session', 'Session ID');
updateAPISessionTable ($installer, 'api_session', 'Session ID');

$installer->endSetup ();

