<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Deferred payment info
 */
class Gamuza_Basic_Block_Payment_Info_Deferred extends Mage_Payment_Block_Info
{
    /**
     * Prepare deferred related payment info
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

        $data = array();

        if ($this->getInfo()->getDeferredInstallmentsQty() !== null)
        {
            $data[Mage::helper('payment')->__('Installments Qty')] = Mage::helper('basic')->__('%d Installments', $this->getInfo()->getDeferredInstallmentsQty());
        }

        if ($this->getInfo()->getDeferredIntervalDays() !== null)
        {
            $data[Mage::helper('payment')->__('Interval Days')] = Mage::helper('basic')->__('%d Days', $this->getInfo()->getDeferredIntervalDays());
        }

        if ($this->getInfo()->getDeferredFirstDueDays() !== null)
        {
            $data[Mage::helper('payment')->__('First Due Days')] = Mage::helper('basic')->__('%d Days', $this->getInfo()->getDeferredFirstDueDays());
        }

        return parent::_prepareSpecificInformation($data);
    }
}

