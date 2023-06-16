<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer's gender source model
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Customer_Gender
{
    public function toOptionArray ()
    {
        $result = array(
            Gamuza_Basic_Helper_Data::CUSTOMER_GENDER_MALE   => Mage::helper ('basic')->__('Male'),
            Gamuza_Basic_Helper_Data::CUSTOMER_GENDER_FEMALE => Mage::helper ('basic')->__('Female'),
        );

        return $result;
    }
}

