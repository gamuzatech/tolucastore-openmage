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
    /**
     * Retrieve information about current Magento installation
     *
     * @return array
     */
    public function info()
    {
        $result = parent::info();

        $result['tolucastore_version'] = Mage::getOpenMageVersion();
        $result['enable_pdv_admin_dashboard'] = Mage::getStoreConfigFlag('admin/dashboard/enable_pdv');

        return $result;
    }

    public function backup ($type = null)
    {
        if (!empty ($type))
        {
            Mage::app ()->getStore ()->setConfig (
                Mage_Backup_Model_Observer::XML_PATH_BACKUP_TYPE, $type
            );
        }

        Mage::getModel ('backup/observer')->scheduledBackup ();

        $backupManager = Mage::registry ('backup_manager');

        $this->_log ($backupManager->getBackupPath ());

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
            $this->_log ('CACHE: enable %s', $type);

            $cacheTypes[$type] = 1;
        }

        foreach ($cacheTypes as $type => $value)
        {
            if (!array_key_exists($type, $codes))
            {
                continue; // skip
            }

            $this->_log ('CACHE: clean %s', $type);

            Mage::app()->getCacheInstance()->cleanType($type);

            Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
        }

        Mage::app()->saveUseCache($cacheTypes);

        if (!empty($codes))
        {
            return true;
        }

        $this->_log ('CACHE: clean all');

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
                    Mage::getModel ('basic/observer')->cleanExpiredBackups ();

                    break;
                }
                case 'api':
                {
                    Mage::getModel ('api/cron')->cleanOldSessions (null);

                    break;
                }
            }

            $this->_log ('CLEAN: %s', $value);
        }

        return true;
    }

    public function logout ()
    {
        // Mage::app()->cleanAllSessions();

        $this->_log ('LOGOUT: files');

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

        $this->_log ('LOGOUT: api');

        $write->delete(Mage::getSingleton('core/resource')->getTableName('api/session'));

        $this->_log ('LOGOUT: core');

        $write->delete(Mage::getSingleton('core/resource')->getTableName('core/session'));

        return true;
    }

    public function session ($codes = array())
    {
        $result = null;

        $user = Mage::getModel('admin/user')->loadByUsername(Gamuza_Basic_Helper_Data::DESKTOP_ADMIN_USER);

        if ($user && $user->getId())
        {
            $session = Mage::getSingleton('admin/session');

            if (!in_array('keep', $codes))
            {
                $session->renewSession();

                $this->_log ('SESSION: renew');
            }

            if (Mage::getSingleton('adminhtml/url')->useSecretKey())
            {
                Mage::getSingleton('adminhtml/url')->renewSecretUrls();
            }

            $session->setIsFirstPageAfterLogin(true);
            $session->setUser($user);
            $session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

            Mage::dispatchEvent('admin_session_user_login_success', array('user' => $user));

            $result = $session->getEncryptedSessionId ();

            $this->_log ('SESSION: admin');
        }
        else
        {
            $this->_fault('user_not_exists');
        }

        return $result;
    }

    private function _log ($text)
    {
        if (!strcmp (php_sapi_name (), 'cli'))
        {
            $args = func_get_args ();

            array_shift ($args);

            echo sprintf ($text, implode (',', $args)) . PHP_EOL;
        }
    }
}

