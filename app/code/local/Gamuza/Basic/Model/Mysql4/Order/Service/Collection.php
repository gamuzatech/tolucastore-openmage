<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Mysql4_Order_Service_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct ()
    {
        $this->_init ('basic/order_service');
    }
}

