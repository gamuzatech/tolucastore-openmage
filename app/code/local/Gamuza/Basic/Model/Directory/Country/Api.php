<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Directory Country Api
 */
class Gamuza_Basic_Model_Directory_Country_Api
    extends Mage_Directory_Model_Country_Api
{
    /**
     * Retrieve countries list
     *
     * @return array
     */
    public function items()
    {
        $countryId = Mage::getStoreConfig ('shipping/origin/country_id');

        $allowedCountries = explode(',', Mage::getStoreConfig('general/country/allow'));

        $collection = Mage::getModel('directory/country')->getCollection()
            ->addFieldToFilter('country_id', array('in' => $allowedCountries))
        ;

        $result = array();

        foreach ($collection as $country)
        {
            /** @var Mage_Directory_Model_Country $country */
            $country->getName(); // Loading name in default locale

            $data = $country->toArray(array('country_id', 'iso2_code', 'iso3_code', 'name'));

            $data['is_default'] = !strcmp($data['country_id'], $countryId);

            $result[] = $data;
        }

        return $result;
    }
}

