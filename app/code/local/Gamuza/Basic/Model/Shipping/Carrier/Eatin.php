<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Shipping_Carrier_Eatin extends Mage_Shipping_Model_Carrier_Abstract
{
    protected $_code = 'eatin';
    protected $_isFixed = true;

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result|false
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active'))
        {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        if (!empty($result))
        {
            $method = Mage::getModel('shipping/rate_result_method');

            $method->setCarrier('eatin');
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod('local');
            $method->setMethodTitle(Mage::helper('shipping')->__('Local Eat In'));

            $method->setPrice(0);
            $method->setCost(0);

            $result->append($method);
        }

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['eatin' => Mage::helper('basic')->__('Local Eat In')];
    }
}

