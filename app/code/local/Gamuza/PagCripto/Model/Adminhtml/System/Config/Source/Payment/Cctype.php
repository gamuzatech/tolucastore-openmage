<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Model_Adminhtml_System_Config_Source_Payment_Cctype
{
    public function toOptionArray ()
    {
        $options =  array ();

        foreach (Mage::getSingleton ('pagcripto/payment_config')->getCcTypes () as $code => $name)
        {
            $options [] = array(
               'value' => $code,
               'label' => $name
            );
        }

        return $options;
    }

    public function toArray ()
    {
        $result = array ();

        foreach ($this->toOptionArray () as $option)
        {
            $result [$option ['value']] = $option ['label'];
        }

        return $result;
    }
}

