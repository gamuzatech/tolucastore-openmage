<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Certificate_Version
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function render (Varien_Data_Form_Element_Abstract $element)
    {
        $directory = Mage::getBaseDir ('var') . DS . 'brazil' . DS . 'cert';

        $filename = $directory . DS . Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_FILENAME);

        if (!is_file ($filename))
        {
            return null;
        }

        $contents = file_get_contents ($filename);
        $password = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_PASSWORD);

        if (!openssl_pkcs12_read ($contents, $certificates, $password))
        {
            return null;
        }

        $result = openssl_x509_parse ($certificates ['cert'], false);

        $validFrom = $result ['validFrom_time_t'];
        $validTo   = $result ['validTo_time_t'];

        $pairs = array(
            Mage::helper ('brazil')->__('Country') => $result ['subject']['countryName'],
            Mage::helper ('brazil')->__('Region')  => $result ['subject']['stateOrProvinceName'],
            Mage::helper ('brazil')->__('City')    => $result ['subject']['localityName'],
            Mage::helper ('brazil')->__('Name')    => $result ['subject']['commonName'],
            Mage::helper ('brazil')->__('Unit')    => implode (', ', $result ['subject']['organizationalUnitName']),
            Mage::helper ('brazil')->__('Version') => $result ['version'],
            Mage::helper ('brazil')->__('From')    => Mage::getModel ('core/date')->gmtDate ('d/m/Y H:i:s', $validFrom),
            Mage::helper ('brazil')->__('To')      => Mage::getModel ('core/date')->gmtDate ('d/m/Y H:i:s', $validTo),
        );

        $pairs = array_map (function ($key, $value) {
            return "<b>{$key}:</b> {$value}";
        }, array_keys ($pairs), $pairs);

        return implode ('<br/>', $pairs);
    }
}

