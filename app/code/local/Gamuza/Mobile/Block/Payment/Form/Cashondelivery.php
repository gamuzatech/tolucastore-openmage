<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Block for Cash On Delivery payment method form
 */
class Gamuza_Mobile_Block_Payment_Form_Cashondelivery extends Mage_Payment_Block_Form_Cashondelivery
{
    /**
     * Block construction. Set block template.
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/mobile/payment/form/cashondelivery.phtml');
    }

    /**
     * Retrieve availables boolean types
     *
     * @return array
     */
    public function getChangeAvailableTypes()
    {
        $result = array(
            0 => Mage::helper('core')->__('No'),
            1 => Mage::helper('core')->__('Yes'),
        );

        return $result;
    }
}

