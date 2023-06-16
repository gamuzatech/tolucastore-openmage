<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http =>//www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Shipping API
 */
class Gamuza_Basic_Model_Shipping_Api extends Mage_Core_Model_Magento_Api
{
    const XML_PATH_STORE_ADDRESS3 = 'shipping/origin/street_line3';
    const XML_PATH_STORE_ADDRESS4 = 'shipping/origin/street_line4';

    public function origin ($storeId = 0)
    {
        $regionId  = Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_REGION_ID, $storeId);
        $countryId = Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_COUNTRY_ID, $storeId);

        $region  = Mage::getModel ('directory/region')->loadById ($regionId, $countryId);
        $country = Mage::getModel ('directory/country')->loadByCode ($countryId);

        $postcode = preg_replace ('[\D]', "", Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_ZIP, $storeId));

        $result = array(
            'street_line1' => Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_ADDRESS1, $storeId),
            'street_line2' => Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_ADDRESS2, $storeId),
            'street_line3' => Mage::getStoreConfig (self::XML_PATH_STORE_ADDRESS3, $storeId),
            'street_line4' => Mage::getStoreConfig (self::XML_PATH_STORE_ADDRESS4, $storeId),
            'postcode' => $postcode,
            'city' => Mage::getStoreConfig (Mage_Shipping_Model_Shipping::XML_PATH_STORE_CITY, $storeId),
            'region_id'  => intval ($regionId),
            'country_id' => $countryId,
            'region_code' => $region->getCode (),
            'region_name' => $region->getName (),
            'country_iso2' => $country->getIso2Code (),
            'country_iso3' => $country->getIso3Code (),
            'country_name' => $country->getName (),
        );

        return $result;
    }
}

