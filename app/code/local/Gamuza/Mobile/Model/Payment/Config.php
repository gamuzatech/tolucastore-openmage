<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Payment configuration model
 *
 * Used for retrieving configuration data by payment models
 */
class Gamuza_Mobile_Model_Payment_Config extends Mage_Payment_Model_Config
{
    /**
     * Retrieve array of credit card types
     *
     * @return array
     */
    public function getCcTypes()
    {
        $_types = Mage::getConfig()->getNode(Gamuza_Mobile_Helper_Data::XML_GLOBAL_PAYMENT_MACHINE_TYPES)->asArray();

        uasort($_types, array('Gamuza_Mobile_Model_Payment_Config', 'compareCcTypes'));

        $types = array();

        foreach ($_types as $data)
        {
            if (isset($data['code']) && isset($data['name']))
            {
                $types[$data['code']] = $data['name'];
            }
        }

        return $types;
    }
}

