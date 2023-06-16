<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Model_Transaction extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('picpay/transaction');
    }
}

