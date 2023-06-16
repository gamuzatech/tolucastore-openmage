<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Model_Message extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('bot/message');
    }
}

