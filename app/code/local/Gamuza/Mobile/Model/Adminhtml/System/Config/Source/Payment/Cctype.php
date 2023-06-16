<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Adminhtml_System_Config_Source_Payment_Cctype
{
    public function toOptionArray()
    {
        $options =  array();

        foreach (Mage::getSingleton('mobile/payment_config')->getCcTypes() as $code => $name)
        {
            $options[] = array(
               'value' => $code,
               'label' => $name
            );
        }

        return $options;
    }
}

