<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Model_Cron
{
    public function runTransaction ()
    {
        Mage::getModel ('picpay/cron_transaction')->run ();
    }
}

