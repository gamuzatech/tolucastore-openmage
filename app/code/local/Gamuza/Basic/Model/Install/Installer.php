<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Installer model
 */
class Gamuza_Basic_Model_Install_Installer extends Mage_Install_Model_Installer
{
    public function finish ()
    {
        parent::finish ();

        self::createDesktopUser ();

        self::applyAllSecurityUpdates ();
    }

    public function createDesktopUser ()
    {
        $role = Mage::getModel ('admin/roles')
            ->load (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_NAME, 'role_name')
            ->setName (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_NAME)
            ->setRoleType ('G')
            ->save ();

        $resourcesList2D = Mage::getModel ('admin/roles')->getResourcesList2D ();

        $resourcesList2D = array_filter ($resourcesList2D, function ($var) {
            return !!strcmp ($var, 'all');
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
            'permission'  => 'deny'
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

        $firstName = strrstr (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_NAME, ' ', true);
        $lastName  = trim (strrstr (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_NAME, ' '));

        $user = Mage::getModel ('admin/user')
            ->loadByUsername (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_USER)
            ->setUsername (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_USER)
            ->setFirstname ($firstName)
            ->setLastname ($lastName)
            ->setEmail (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_EMAIL)
            ->setApiKey (hash ('sha512', uniqid(rand (), true)))
            ->setIsActive (true)
            ->save ();

        $user->setRoleIds (array ($role->getId ()))
            ->saveRelations ();
    }

    public static function applyAllSecurityUpdates ()
    {
        $adminUser = Mage::getModel ('admin/user')->loadByUsername ('admin');

        if ($adminUser && $adminUser->getId ())
        {
            $adminUser->getResource ()->save ($adminUser->setIsSystem (true));
        }

        $adminUser = Mage::getModel ('admin/user')->loadByUsername (Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_USER);

        if ($adminUser && $adminUser->getId ())
        {
            $adminUser->getResource()->save($adminUser->setIsSystem(true));
        }

        $apiUser = Mage::getModel ('api/user')->loadByUsername (Gamuza_Basic_Helper_Data::DESKTOP_API_USER);

        if ($apiUser && $apiUser->getId ())
        {
            $apiUser->getResource()->save($apiUser->setIsSystem(true));
        }

        Mage::dispatchEvent('basic_install_installer_finish_after');
    }
}

