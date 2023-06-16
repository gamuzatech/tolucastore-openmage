<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Model_Cron
{
    public function runTransaction ()
    {
        Mage::getModel ('openpix/cron_transaction')->run ();
    }
}

