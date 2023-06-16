<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Model_Checkout_Cart_Payment_Api
    extends Mage_Checkout_Model_Cart_Payment_Api
{
    /**
     * @param Mage_Payment_Model_Method_Abstract $method
     * @return array|null
     */
    protected function _getPaymentMethodAvailableCcTypes($method)
    {
        $methodCcTypes = explode(',', $method->getConfigData('cctypes'));

        $result = array ();

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_Mobile'))
        {
            $ccTypes = Mage::getSingleton('mobile/payment_config')->getCcTypes();

            foreach ($ccTypes as $code => $title)
            {
                if (in_array($code, $methodCcTypes))
                {
                    $result[$code] = $title;
                }
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PagCripto'))
        {
            $ccTypes = Mage::getSingleton('pagcripto/payment_config')->getCcTypes();

            foreach ($ccTypes as $code => $title)
            {
                if (in_array($code, $methodCcTypes))
                {
                    $result[$code] = $title;
                }
            }
        }

        if (empty($result))
        {
            return null;
        }

        return $result;
    }
}

