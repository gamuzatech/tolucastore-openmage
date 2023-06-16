<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Adminhtml_System_Config_Source_Shipping_Allmethods
{
    public function toArray ()
    {
        $result = array ();

        foreach (Mage::getSingleton ('shipping/config')->getAllCarriers () as $shipping)
        {
            $result [$shipping->getId ()] = $shipping->getConfigData ('title');
        }

        return $result;
    }
}

