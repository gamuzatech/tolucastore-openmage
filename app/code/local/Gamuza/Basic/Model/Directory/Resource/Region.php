<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http =>//www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Directory Region Resource Model
 */
class Gamuza_Basic_Model_Directory_Resource_Region
    extends Mage_Directory_Model_Resource_Region
{
    public function loadById (Mage_Directory_Model_Region $region, $regionCode, $countryId)
    {
        return $this->_loadByCountry ($region, $countryId, $regionCode, 'region_id');
    }
}

