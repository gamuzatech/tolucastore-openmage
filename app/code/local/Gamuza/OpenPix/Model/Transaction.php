<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Model_Transaction extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('openpix/transaction');
    }
}

