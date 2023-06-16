<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Mysql4_Log extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct ()
    {
        $this->_init ('pdv/log', 'entity_id');
    }
}

