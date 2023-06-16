<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Block_Payment_Info_Machineondelivery extends Mage_Payment_Block_Info
{
    /**
     * Retrieve credit card type name
     *
     * @return string
     */
    public function getCcTypeName()
    {
        $types = Mage::getSingleton('mobile/payment_config')->getCcTypes();

        $ccType = $this->getInfo()->getCcType();

        if (isset($types[$ccType]))
        {
            return $types[$ccType];
        }

        return (empty($ccType)) ? Mage::helper('payment')->__('N/A') : $ccType;
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

        if ($ccType = $this->getCcTypeName())
        {
            $data[Mage::helper('payment')->__('Card Type')] = $ccType;
        }

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}

