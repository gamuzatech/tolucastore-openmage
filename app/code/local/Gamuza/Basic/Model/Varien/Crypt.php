<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Crypt factory
 */
class Gamuza_Basic_Model_Varien_Crypt
{
    /**
     * Factory method to return requested cipher logic
     *
     * @param string $method
     * @return Varien_Crypt_Abstract
     * @throws Varien_Exception
     */
    static public function factory($method = 'auto')
    {
        /**
         * Try to resolve best scenario:
         * 1. mcrypt extension and it's not deprecated
         * 2. mcrypt polyfill
         * 3. openssl
         */
        if ($method == 'auto')
        {
            $hasMcrypt = extension_loaded('mcrypt');
            $hasMcryptPolyfill = !$hasMcrypt && function_exists('mcrypt_module_open');

            if (($hasMcrypt && version_compare(PHP_VERSION, '7.1.0', '<')) || $hasMcryptPolyfill)
            {
                $method = 'mcrypt';
            }
            else if (extension_loaded('openssl'))
            {
                $method = 'openssl';
            }
        }

        switch ($method)
        {
            case 'openssl':
            {
                $crypt = new Gamuza_Basic_Model_Varien_Crypt_Openssl();

                break;
            }
            case 'mcrypt':
            {
                $crypt = new Varien_Crypt_Mcrypt();

                break;
            }
            default:
            {
                throw new Varien_Exception('Crypt adapter not available.');
            }
        }

        return $crypt;
    }
}

