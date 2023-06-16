<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Block for Cash On Delivery payment method form
 */
class Gamuza_Mobile_Block_Payment_Form_Machineondelivery extends Mage_Payment_Block_Form_Cashondelivery
{
    /**
     * Block construction. Set block template.
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/mobile/payment/form/machineondelivery.phtml');
    }

    /**
     * Retrieve payment configuration object
     *
     * @return Mage_Payment_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('mobile/payment_config');
    }

    /**
     * Retrieve availables credit card types
     *
     * @return array
     */
    public function getCcAvailableTypes()
    {
        $types = $this->_getConfig()->getCcTypes();

        if ($method = $this->getMethod())
        {
            $availableTypes = $method->getConfigData('cctypes');

            if ($availableTypes)
            {
                $availableTypes = explode(',', $availableTypes);

                foreach ($types as $code=>$name)
                {
                    if (!in_array($code, $availableTypes))
                    {
                        unset($types[$code]);
                    }
                }
            }
        }

        return $types;
    }
}

