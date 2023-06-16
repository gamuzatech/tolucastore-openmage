<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('pdv_setup');
$installer->startSetup ();

$role = Mage::getModel('api/roles')
    ->load(Toluca_PDV_Helper_Data::PDV_API_NAME, 'role_name')
    ->setName(Toluca_PDV_Helper_Data::PDV_API_NAME)
    ->setRoleType('G')
    ->save();

$resourcesList2D = Mage::getModel('api/roles')->getResourcesList2D();

$resourcesList2D = array_filter($resourcesList2D, function($var) {
    return !strcmp($var, 'all');
});

/*
Mage::getModel('api/rules')
    ->setRoleId($role->getId())
    ->setResources($resourcesList2D)
    ->saveRel();
*/

$row = array(
    'role_id'         => $role->getId(),
    'resource_id'     => 'all',
    'role_type'       => 'G',
    'api_permission'  => 'allow'
);

$resource = Mage::getSingleton ('core/resource');

$write = $resource->getConnection ('core_write');
$table = $resource->getTableName ('api/rule');

$write->beginTransaction ();

try
{
    $collection = Mage::getResourceModel ('api/rules_collection')
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

$firstName = strrstr(Toluca_PDV_Helper_Data::PDV_API_NAME, ' ', true);
$lastName  = trim(strrstr(Toluca_PDV_Helper_Data::PDV_API_NAME, ' '));

$user = Mage::getModel('api/user')
    ->loadByUsername(Toluca_PDV_Helper_Data::PDV_API_USER)
    ->setUsername(Toluca_PDV_Helper_Data::PDV_API_USER)
    ->setFirstname($firstName)
    ->setLastname($lastName)
    ->setEmail(Toluca_PDV_Helper_Data::PDV_API_EMAIL)
    ->setApiKey(hash('sha512', uniqid(rand(), true)))
    ->setIsActive(true)
    ->save();

$user->setRoleIds(array($role->getId()))
    ->saveRelations();

$installer->endSetup ();

