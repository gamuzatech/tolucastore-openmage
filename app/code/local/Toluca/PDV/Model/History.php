<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_History extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('pdv/history');
    }

    protected function _afterSaveCommit ()
    {
        $openAmount      = floatval ($this->getOpenAmount ());
        $reinforceAmount = floatval ($this->getReinforceAmount ());
        $bleedAmount     = floatval ($this->getBleedAmount ());

        $moneyAmount  = floatval ($this->getMoneyAmount ());
        $changeAmount = floatval ($this->getChangeAmount ());

        $closeAmount = ((($openAmount + $reinforceAmount) + $bleedAmount) + $moneyAmount) + $changeAmount;

        $machineAmount = floatval ($this->getMachineAmount ());
        $pagcriptoAmount = floatval ($this->getPagcriptoAmount ());
        $picpayAmount    = floatval ($this->getPicpayAmount ());
        $openpixAmount   = floatval ($this->getOpenpixAmount ());
        $creditcardAmount   = floatval ($this->getCreditcardAmount ());
        $billetAmount       = floatval ($this->getBilletAmount ());
        $banktransferAmount = floatval ($this->getBanktransferAmount ());
        $checkAmount        = floatval ($this->getCheckAmount ());
        $pixAmount          = floatval ($this->getPixAmount ());

        $this->setTotalAmount (
            $closeAmount + $machineAmount
            + $pagcriptoAmount + $picpayAmount + $openpixAmount
            + $creditcardAmount + $billetAmount + $banktransferAmount + $checkAmount + $pixAmount
        );

        $this->_getResource ()->save ($this); // total_amount
    }
}

