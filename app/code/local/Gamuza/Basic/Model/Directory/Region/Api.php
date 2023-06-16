<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Directory Region Api
 */
class Gamuza_Basic_Model_Directory_Region_Api
    extends Mage_Directory_Model_Region_Api
{
    /**
     * Retrieve regions list
     *
     * @param string $country
     * @return array
     */
    public function items($country)
    {
        $regionId = Mage::getStoreConfig ('shipping/origin/region_id');

        try
        {
            $country = Mage::getModel('directory/country')->loadByCode($country);
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('country_not_exists', $e->getMessage());
        }

        if (!$country->getId())
        {
            $this->_fault('country_not_exists');
        }

        $result = [];

        foreach ($country->getRegions() as $region)
        {
            $result[] = array(
                'region_id' => $region->getRegionId(),
                'code' => $region->getCode(),
                'name' => $region->getName(), //use the logic of default name
                'is_default' => !strcmp($region->getId(), $regionId),
            );
        }

        return $result;
    }
}

