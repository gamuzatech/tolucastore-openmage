<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Magento API
 */
class Gamuza_Basic_Model_Magento_Api extends Mage_Core_Model_Magento_Api
{
    public function backup ()
    {
        Mage::getModel ('backup/observer')->scheduledBackup ();

        return true;
    }

    public function cache($codes = array())
    {
        if (!empty($codes))
        {
            $codes = array_flip($codes);
        }

        $cacheTypes = Mage::helper('core')->getCacheTypes();

        foreach ($cacheTypes as $type => $value)
        {
            $cacheTypes[$type] = 1;

            if (!array_key_exists($type, $codes))
            {
                continue; // skip
            }

            Mage::app()->getCacheInstance()->cleanType($type);

            Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
        }

        Mage::app()->saveUseCache($cacheTypes);

        if (!empty($codes))
        {
            return true;
        }

        Mage::app()->cleanCache();

        Mage::dispatchEvent('adminhtml_cache_flush_system');

        Mage::app()->getCacheInstance()->flush();

        Mage::dispatchEvent('adminhtml_cache_flush_all');

        return true;
    }

    public function clean ($codes = array())
    {
        foreach ($codes as $id => $value)
        {
            switch ($value)
            {
                case 'quote':
                {
                    Mage::getModel ('basic/observer')->cleanExpiredQuotes ();

                    break;
                }
                case 'chat':
                {
                    if (Mage::helper ('core')->isModuleEnabled ('Toluca_Bot'))
                    {
                        Mage::getModel ('bot/observer')->cleanExpiredChats ();
                    }

                    break;
                }
                case 'backup':
                {
                    $point = date ('c', strtotime ('-7 days'));

                    foreach (Mage::getModel ('backup/fs_collection') as $fs)
                    {
                        $stamp = date ('c', $fs->getTime ());

                        if ($stamp < $point)
                        {
                            $backup = Mage::getModel ('backup/backup')->loadByTimeAndType ($fs->getTime (), $fs->getType ());

                            try
                            {
                                $backup->deleteFile();
                            }
                            catch (Exception $e)
                            {
                                Mage::logException ($e);
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    public function logout ()
    {
        // Mage::app()->cleanAllSessions();

        $dir = Mage::app()->getConfig()->getOptions()->getSessionDir();

        $dh  = scandir($dir);

        foreach ($dh as $file)
        {
            if (strpos ($file, 'sess_') !== false)
            {
                unlink ($dir . DS . $file);
            }
        }

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');

        $write->delete(Mage::getSingleton('core/resource')->getTableName('api/session'));
        $write->delete(Mage::getSingleton('core/resource')->getTableName('core/session'));

        return true;
    }

    public function session ()
    {
        $result = null;

        $user = Mage::getModel('admin/user')->loadByUsername(Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_USER);

        if ($user && $user->getId())
        {
            $session = Mage::getSingleton('admin/session');

            $session->renewSession();

            if (Mage::getSingleton('adminhtml/url')->useSecretKey())
            {
                Mage::getSingleton('adminhtml/url')->renewSecretUrls();
            }

            $session->setIsFirstPageAfterLogin(true);
            $session->setUser($user);
            $session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

            Mage::dispatchEvent('admin_session_user_login_success', array('user' => $user));

            $result = $session->getEncryptedSessionId ();
        }
        else
        {
            $this->_fault('user_not_exists');
        }

        return $result;
    }
}

