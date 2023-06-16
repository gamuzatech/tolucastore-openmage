<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Model_Cron
{
    public function runTransaction ()
    {
        Mage::getModel ('pagcripto/cron_transaction')->run ();
    }
}

