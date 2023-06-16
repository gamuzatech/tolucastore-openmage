<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Payment_Info_Pix extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/brazil/payment/info/pix.phtml');
    }

    /**
     * Prepare credit card related payment info
     *
     * @param Varien_Object|array $transport
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation)
        {
            return $this->_paymentSpecificInformation;
        }

        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        $amount = Mage::helper('core')->currency($this->getInfo()->getBaseAmountOrdered(), true, false);
        $data[Mage::helper('payment')->__('Amount')] = $amount;


        return $transport->setData(array_merge($transport->getData(), $data));
    }
}

