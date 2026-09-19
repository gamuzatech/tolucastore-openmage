<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Certificate_Cryptoki
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function render (Varien_Data_Form_Element_Abstract $element)
    {
        $middleware = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_MIDDLEWARE);

        if (empty ($middleware))
        {
            return null;
        }

        $filename = strval (Mage::getConfig ()->getNode (sprintf ('global/middleware/%s/%s', $middleware, PHP_OS_FAMILY)));

        if (!is_file ($filename))
        {
            $result = Mage::helper ('brazil')->__('Certificate file does not exist.') . PHP_EOL . $filename;

            return nl2br ($result);
        }

        $password = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_PASSWORD);

        putenv ('PATH=/opt/local/bin:/usr/local/bin:/usr/bin');

        $command = sprintf ('pkcs11-tool --module %s --login --pin %s --list-objects --verbose 2>&1', $filename, $password);

        $result = PHP_EOL . shell_exec ($command) . PHP_EOL;

        return nl2br ($result);
    }
}

