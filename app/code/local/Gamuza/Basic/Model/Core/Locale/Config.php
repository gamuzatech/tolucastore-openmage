<?php
/**
 * @package     Gamuza_Store
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Core_Locale_Config
    extends Mage_Core_Model_Locale_Config
{
    protected $_allowedLocales = array(
        'pt_BR' /* Portuguese (Brazil) */,
        'en_US' /* English (United States) */,
        'es_ES' /* Spanish (Spain) */
    );

    protected $_allowedCurrencies = array(
        'BRL' /* Brazilian Real */,
        'USD' /* US Dollar */,
        'EUR' /* Euro */
    );
}

