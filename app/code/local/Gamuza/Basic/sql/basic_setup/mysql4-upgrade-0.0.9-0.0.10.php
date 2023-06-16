<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$user = Mage::getModel ('admin/user')->loadByUsername ('admin');

$role = Mage::getModel ('admin/roles')->load ($user->getRole ()->getId ());

$resourcesList2D = Mage::getModel('admin/roles')->getResourcesList2D();

$resourcesList2D = array_filter ($resourcesList2D, function($var) {
    return !strcmp ($var, 'all');
});

/*
Mage::getModel ('admin/rules')
    ->setRoleId ($role->getId ())
    ->setResources ($resourcesList2D)
    ->saveRel ();
*/

$row = array(
    'role_id'     => $role->getId (),
    'resource_id' => 'all',
    'role_type'   => 'G',
    'permission'  => 'allow'
);

$resource = Mage::getSingleton ('core/resource');

$write = $resource->getConnection ('core_write');
$table = $resource->getTableName ('admin/rule');

$write->beginTransaction ();

try
{
    $collection = Mage::getResourceModel ('admin/rules_collection')
        ->getByRoles ($role->getId ())
        ->walk ('delete');

    foreach ($resourcesList2D as $resource)
    {
        $row ['resource_id'] = $resource;

        $write->insert ($table, $row);
    }

    $write->commit ();
}
catch (Exception $e)
{
    $write->rollback ();
}

$installer->endSetup ();

