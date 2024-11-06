<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('lib', 'Gamuza_Brazil') . DS . 'lib' . DS . 'php_qrcode_pix' . DS . 'funcoes_pix.php');

class Gamuza_Brazil_Helper_Pix extends Mage_Core_Helper_Abstract
{
    public function _getKey ($order)
    {
        $company = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_COMPANY);
        $name    = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_NAME);

        $city = sprintf ('%s %s', $order->getBillingAddress ()->getCity (), $order->getBillingAddress ()->getRegionCode ());

        $pix[00] = '01'; // Payload Format Indicator - FIXED: 01
        $pix[01] = '12'; // Use Only Once!
        $pix[26][00] = 'br.gov.bcb.pix';
        $pix[26][01] = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_PAYMENT_GAMUZA_BRAZIL_PIX_KEY);
        $pix[26][02] = sprintf (
            '%s-%s-%s',
            Mage::helper ('brazil')->__('Order'),
            $order->getIncrementId (),
            preg_replace('/\s+/', "", $name)
        );

        $pix[52] = '0000'; // Merchant Category Code '0000' or MCC ISO18245
        $pix[53] = '986';  // BRL: brazilian real - ISO4217
        $pix[54] = number_format ($order->getBaseGrandTotal (), 2, '.', "");
        $pix[58] = 'BR';  // Country code ISO3166-1 alpha 2
        $pix[59] = substr ($company, 0, 25);
        $pix[60] = substr ($city, 0, 15);
        $pix[62][05] = "***"; // Transaction identifier '***' AUTOMATIC

        $result = montaPix ($pix);

        $result .= '6304'; // CRC
        $result .= crcChecksum ($result);

        return $result;
    }
}

