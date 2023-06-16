<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http =>//www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Directory_Region extends Mage_Directory_Model_Region
{
    public function loadById ($regionId, $countryId)
    {
        $this->_getResource ()->loadById ($this, $regionId, $countryId);

        return $this;
    }
}

