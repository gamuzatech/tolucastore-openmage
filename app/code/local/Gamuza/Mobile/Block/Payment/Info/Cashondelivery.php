<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cash on delivery payment info
 */
class Gamuza_Mobile_Block_Payment_Info_Cashondelivery extends Mage_Payment_Block_Info
{
    /**
     * Retrieve change type name
     *
     * @return string
     */
    public function getChangeTypeName()
    {
        $types = array(
            0 => Mage::helper('core')->__('No'),
            1 => Mage::helper('core')->__('Yes'),
        );
        $changeType = $this->getInfo()->getAdditionalInformation('change_type');

        if (isset($types[$changeType]))
        {
            return $types[$changeType];
        }

        return (empty($changeType)) ? Mage::helper('payment')->__('N/A') : $changeType;
    }

    /**
     * Prepare cash on delivery related payment info
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

        if ($changeType = $this->getChangeTypeName())
        {
            $data[Mage::helper('payment')->__('Need Change')] = $changeType;
        }

        if ($this->getInfo()->getAdditionalInformation('change_type') && $this->getInfo()->getAdditionalInformation('cash_amount'))
        {
            $data[Mage::helper('payment')->__('Cash Amount')]   = Mage::helper('core')->currency ($this->getInfo()->getAdditionalInformation('cash_amount'), true, false);
            $data[Mage::helper('payment')->__('Change Amount')] = Mage::helper('core')->currency ($this->getInfo()->getAdditionalInformation('change_amount'), true, false);
        }

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}

