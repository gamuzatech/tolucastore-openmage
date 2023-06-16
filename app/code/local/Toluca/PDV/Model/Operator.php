<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Operator extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('pdv/operator');
    }

    public function getCashierStatus ()
    {
        if ($this->getId () && $this->getCashierId ())
        {
            $cashier = Mage::getModel ('pdv/cashier')->load ($this->getCashierId ());

            if ($cashier && $cashier->getId ())
            {
                return $cashier->getStatus ();
            }
        }
    }
}

