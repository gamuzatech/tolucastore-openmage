<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Adminhtml_System_Config_Source_Payment_Allmethods
{
    public function toArray ()
    {
        $result = array ();

        foreach (Mage::getSingleton ('payment/config')->getAllMethods () as $payment)
        {
            $result [$payment->getId ()] = $payment->getConfigData ('title');
        }

        return $result;
    }
}

