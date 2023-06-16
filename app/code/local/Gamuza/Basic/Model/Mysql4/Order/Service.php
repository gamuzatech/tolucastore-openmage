<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Mysql4_Order_Service extends Mage_Sales_Model_Resource_Order_Abstract
{
    protected $_useIncrementId = true;

    protected $_entityTypeForIncrementId = 'service';

    protected function _construct ()
    {
        $this->_init ('basic/order_service', 'entity_id');
    }
}

