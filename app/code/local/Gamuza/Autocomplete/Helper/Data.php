<?php
/**
 * @package     Gamuza_Autocomplete
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Autocomplete_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VIACEP_URL = 'https://viacep.com.br/ws/{zipcode}/json';

    public function cep ($zipcode)
    {
	    $curl = curl_init ();

	    curl_setopt ($curl, CURLOPT_URL, str_replace ('{zipcode}', $zipcode, self::VIACEP_URL));
	    curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 12);
	    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 2);

	    $result = curl_exec ($curl);

	    curl_close ($curl);

        return $result;
    }
}

