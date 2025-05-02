<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Html page block
 */
class Gamuza_Mobile_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
{
    public function setAvailableLimit (array $limits)
    {
        $this->_availableLimit = $limits;

        return $this;
    }
}

