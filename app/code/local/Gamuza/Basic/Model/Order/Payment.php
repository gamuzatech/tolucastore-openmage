<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Order_Payment extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('basic/order_payment');
    }
}

