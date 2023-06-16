<?php
/**
 * @package     Gamuza_Autocomplete
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Autocomplete CEP API
 */
class Gamuza_Autocomplete_Model_Cep_Api extends Mage_Api_Model_Resource_Abstract
{
    public function info ($zipcode)
    {
        $response = Mage::helper ('autocomplete')->cep ($zipcode);

        $result = json_decode ($response, true);

        if (!is_array ($result) || !count ($result) || array_key_exists ('erro', $result))
        {
            $this->_fault ('zipcode_not_exists');
        }

        return $result;
    }
}

