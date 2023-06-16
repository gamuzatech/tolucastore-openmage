<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cashier API
 */
class Toluca_PDV_Model_Cashier_Api extends Mage_Api_Model_Resource_Abstract
{
    const XML_PATH_PDV_CASHIER_INCLUDE_ALL_ORDERS = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_INCLUDE_ALL_ORDERS;

    public function info ($cashier_id)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        $cashier = Mage::getModel ('pdv/cashier')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $cashier_id))
            ->getFirstItem ()
        ;

        if (!$cashier || !$cashier->getId ())
        {
            $this->_fault ('cashier_not_exists');
        }

        $result = array(
            'entity_id'  => intval ($cashier->getId ()),
            'code'       => $cashier->getCode (),
            'name'       => $cashier->getName (),
            'is_active'  => boolval ($cashier->getIsActive ()),
            'status'     => intval ($cashier->getStatus ()),
            'operator_id'    => intval ($cashier->getOperatorId ()),
            'operator_code'  => $cashier->getOperatorCode (),
            'operator_name'  => $cashier->getOperatorName (),
            'history_id' => intval ($cashier->getHistoryId ()),
            'created_at' => $cashier->getCreatedAt (),
            'updated_at' => $cashier->getUpdatedAt (),
            'order_amount'     => floatval ($cashier->getOrderAmount ()),
            'customer_id' => intval ($cashier->getCustomerId ()),
            'quote_id'    => intval ($cashier->getQuoteId ()),
            'history' => $cashier->getHistory (),
        );

        $operator = Mage::getModel ('pdv/operator')->load ($cashier->getOperatorId ());

        if ($operator && $operator->getId ())
        {
            $result ['operator_code'] = $operator->getCode ();
            $result ['operator_name'] = $operator->getName ();
        }

        $history = Mage::getModel ('pdv/history')->load ($cashier->getHistoryId ());

        if ($history && $history->getId ())
        {
            $result ['history'] = array(
                'entity_id'        => intval ($history->getId ()),
                'open_amount'      => floatval ($history->getOpenAmount ()),
                'reinforce_amount' => floatval ($history->getReinforceAmount ()),
                'bleed_amount'     => floatval ($history->getBleedAmount ()),
                'close_amount'     => floatval ($history->getCloseAmount ()),
                'opened_at' => $history->getOpenedAt (),
                'closed_at' => $history->getClosedAt (),
                'money_amount'   => floatval ($history->getMoneyAmount ()),
                'change_amount'  => floatval ($history->getChangeAmount ()),
                'machine_amount' => floatval ($history->getMachineAmount ()),
                'pagcripto_amount' => floatval ($history->getPagcriptoAmount ()),
                'picpay_amount'    => floatval ($history->getPicpayAmount ()),
                'openpix_amount'   => floatval ($history->getOpenpixAmount ()),
                'creditcard_amount'   => floatval ($history->getCreditcardAmount ()),
                'billet_amount'       => floatval ($history->getBilletAmount ()),
                'banktransfer_amount' => floatval ($history->getBanktransferAmount ()),
                'check_amount'        => floatval ($history->getCheckAmount ()),
                'pix_amount'          => floatval ($history->getPixAmount ()),
                'shipping_amount' => floatval ($history->getShippingAmount ()),
                'total_amount'    => floatval ($history->getTotalAmount ()),
                'created_at' => $history->getCreatedAt (),
                'updated_at' => $history->getUpdatedAt (),
            );
        }

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('entity_id', array ('eq' => $cashier->getQuoteId ()))
            ->addFieldToFilter ('pdv_customer_id', array ('eq' => $cashier->getCustomerId ()))
        ;

        if (!$collection->getSize ())
        {
            $result ['quote_id'] = 0;
            $result ['customer_id'] = 0;
        }

        $collection = $this->_getOrderCollection ($cashier, $operator, $history);

        $collection->getSelect ()
            ->columns (array(
                'sum_base_grand_total' => 'SUM(base_grand_total)'
            ))
        ;

        if ($collection->getSize () > 0)
        {
            $result ['order_amount'] = floatval ($collection->getFirstItem ()->getSumBaseGrandTotal ());
        }

        return $result;
    }

    public function draft ($cashier_id, $operation = null)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        $cashier = Mage::getModel ('pdv/cashier')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $cashier_id))
            ->getFirstItem ()
        ;

        if (!$cashier || !$cashier->getId ())
        {
            $this->_fault ('cashier_not_exists');
        }

        $operator = Mage::getModel ('pdv/operator')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $cashier->getOperatorId ()))
            ->getFirstItem ()
        ;

        if (!$operator || !$operator->getId ())
        {
            $this->_fault ('operator_not_exists');
        }

        $history = Mage::getModel ('pdv/history')->load ($cashier->getHistoryId ());

        if (!$history || !$history->getId ())
        {
            $this->_fault ('history_not_exists');
        }

        $collection = $this->_getOrderCollection ($cashier, $operator, $history);

        $collection->getSelect ()
            ->columns (array(
                'sum_base_grand_total' => 'SUM(base_grand_total)'
            ))
        ;

        if ($collection->getSize () > 0)
        {
            $history->setOrderAmount (floatval ($collection->getFirstItem ()->getSumBaseGrandTotal ()));
        }

        $result = Mage::app ()
            ->getLayout ()
            ->createBlock ('pdv/adminhtml_cashier_draft')
            ->setArea (Mage_Core_Model_App_Area::AREA_ADMINHTML)
            ->setOperation ($operation)
            ->setCashier ($cashier)
            ->setOperator ($operator)
            ->setHistory ($history)
            ->setTemplate ('toluca/pdv/cashier/draft.phtml')
            ->toHtml ();

        return $result;
    }

    public function clear ($cashier_id)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        $cashier = Mage::getModel ('pdv/cashier')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $cashier_id))
            ->getFirstItem ()
        ;

        if (!$cashier || !$cashier->getId ())
        {
            $this->_fault ('cashier_not_exists');
        }

        $cashier->setQuoteId (0)
            ->setCustomerId (0)
            ->save ()
        ;

        return true;
    }

    public function open ($operator_id, $password, $amount, $message)
    {
        $cashier = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getStatus () == Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED)
        {
            /*
            $this->_fault ('cashier_already_opened');
            */

            return intval ($cashier->getId ());
        }

        $history = Mage::getModel ('pdv/history')
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator_id)
            ->setOpenAmount ($amount)
            ->setReinforceAmount (0)
            ->setBleedAmount (0)
            ->setCloseAmount (0)
            ->setOpenedAt (date ('c'))
            ->setMoneyAmount (0)
            ->setChangeAmount (0)
            ->setMachineAmount (0)
            ->setPagcriptoAmount (0)
            ->setPicpayAmount (0)
            ->setOpenpixAmount (0)
            ->setCreditcardAmount (0)
            ->setBilletAmount (0)
            ->setBanktransferAmount (0)
            ->setCheckAmount (0)
            ->setPixAmount (0)
            ->setShippingAmount (0)
            ->setTotalAmount (0)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        $cashier->setStatus (Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED)
            ->setOperatorId ($operator_id)
            ->setHistoryId ($history->getId ())
            ->setSequenceId (0)
            ->setQuoteId (0)
            ->setCustomerId (0)
            ->setOpenedAt (date ('c'))
            ->save ()
        ;

        $log = Mage::getModel ('pdv/log')
            ->setTypeId (Toluca_PDV_Helper_Data::LOG_TYPE_OPEN)
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator_id)
            ->setHistoryId ($history->getId ())
            ->setTotalAmount ($amount)
            ->setMessage ($message)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function reinforce ($operator_id, $password, $amount, $message)
    {
        $cashier = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getStatus () == Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED)
        {
            $this->_fault ('cashier_already_closed');
        }

        $history = Mage::getModel ('pdv/history')->load ($cashier->getHistoryId ());

        if (!$history || !$history->getId ())
        {
            $this->_fault ('history_not_exists');
        }

        $reinforceAmount = floatval ($history->getReinforceAmount ());

        $history->setReinforceAmount ($reinforceAmount + $amount)
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        $log = Mage::getModel ('pdv/log')
            ->setTypeId (Toluca_PDV_Helper_Data::LOG_TYPE_REINFORCE)
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator_id)
            ->setHistoryId ($history->getId())
            ->setTotalAmount ($amount)
            ->setMessage ($message)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function bleed ($operator_id, $password, $amount, $message)
    {
        $cashier = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getStatus () == Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED)
        {
            $this->_fault ('cashier_already_closed');
        }

        $history = Mage::getModel ('pdv/history')->load ($cashier->getHistoryId ());

        if (!$history || !$history->getId ())
        {
            $this->_fault ('history_not_exists');
        }

        $openAmount = floatval ($history->getOpenAmount ());
        $reinforceAmount = floatval ($history->getReinforceAmount ());
        $bleedAmount  = floatval ($history->getBleedAmount ());
        $moneyAmount  = floatval ($history->getMoneyAmount ());
        $changeAmount = floatval ($history->getChangeAmount ());

        $openpixAmount = floatval ($history->getOpenpixAmount ());
        $checkAmount = floatval ($history->getCheckAmount ());
        $pixAmount = floatval ($history->getPixAmount ());

        $closeAmount = ((($openAmount + $reinforceAmount) + $bleedAmount) + $moneyAmount) + $changeAmount
            + $openpixAmount + $checkAmount + $pixAmount;

        if ($amount > $closeAmount)
        {
            $closeAmount = Mage::helper ('core')->currency ($closeAmount, true, false);

            $message = Mage::helper ('pdv')->__('Bleed amount is invalid. Allowed only %s.', $closeAmount);

            $this->_fault ('cashier_invalid_amount', $message);
        }

        $history->setBleedAmount ($bleedAmount + (- $amount))
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        $log = Mage::getModel ('pdv/log')
            ->setTypeId (Toluca_PDV_Helper_Data::LOG_TYPE_BLEED)
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator_id)
            ->setHistoryId ($history->getId ())
            ->setTotalAmount (- $amount)
            ->setMessage ($message)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function close ($operator_id, $password, $amount, $message)
    {
        $cashier = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getStatus () == Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED)
        {
            $this->_fault ('cashier_already_closed');
        }

        $history = Mage::getModel ('pdv/history')->load ($cashier->getHistoryId ());

        if (!$history || !$history->getId ())
        {
            $this->_fault ('history_not_exists');
        }

        $openAmount      = floatval ($history->getOpenAmount ());
        $reinforceAmount = floatval ($history->getReinforceAmount ());
        $bleedAmount     = floatval ($history->getBleedAmount ());
        $moneyAmount     = floatval ($history->getMoneyAmount ());
        $changeAmount    = floatval ($history->getChangeAmount ());

        $closeAmount = ((($openAmount + $reinforceAmount) + $bleedAmount) + $moneyAmount) + $changeAmount;

        $orderAmount = 0;

        if (Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_INCLUDE_ALL_ORDERS))
        {
            $machineAmount = floatval ($history->getMachineAmount ());
            $pagcriptoAmount = floatval ($history->getPagcriptoAmount ());
            $picpayAmount    = floatval ($history->getPicpayAmount ());
            $openpixAmount   = floatval ($history->getOpenpixAmount ());
            $creditcardAmount   = floatval ($history->getCreditcardAmount ());
            $billetAmount       = floatval ($history->getBilletAmount ());
            $banktransferAmount = floatval ($history->getBanktransferAmount ());
            $checkAmount        = floatval ($history->getCheckAmount ());
            $pixAmount          = floatval ($history->getPixAmount ());

            $orderAmount = $machineAmount
                + $pagcriptoAmount + $picpayAmount + $openpixAmount
                + $creditcardAmount + $billetAmount + $banktransferAmount + $checkAmount + $pixAmount
            ;
        }

        $differenceAmount = round ($amount - ($closeAmount + $orderAmount), 4);

        if ($differenceAmount != 0)
        {
            $differenceAmount = Mage::helper ('core')->currency ($differenceAmount, true, false);

            $message = Mage::helper ('pdv')->__('Close amount is invalid. Difference of %s.', $differenceAmount);

            $this->_fault ('cashier_invalid_amount', $message);
        }

        $history->setCloseAmount (- $amount)
            ->setClosedAt (date ('c'))
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        $cashier->setStatus (Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED)
            ->setClosedAt (date ('c'))
            ->save ()
        ;

        $log = Mage::getModel ('pdv/log')
            ->setTypeId (Toluca_PDV_Helper_Data::LOG_TYPE_CLOSE)
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator_id)
            ->setHistoryId ($history->getId ())
            ->setTotalAmount (- $amount)
            ->setMessage ($message)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    protected function _getCashier ($operator_id, $password, $amount)
    {
        if (empty ($amount))
        {
            $this->_fault ('amount_not_specified');
        }

        if (!is_numeric ($amount))
        {
            $this->_fault ('cashier_invalid_amount');
        }

        if (empty ($operator_id))
        {
            $this->_fault ('operator_not_specified');
        }

        if (empty ($password))
        {
            $this->_fault ('password_not_specified');
        }

        $operator = Mage::getModel ('pdv/operator')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $operator_id))
            ->getFirstItem ()
        ;

        if (!$operator || !$operator->getId ())
        {
            $this->_fault ('operator_not_exists');
        }

        $password = Mage::helper ('core')->getHash ($password, true);

        if (strcmp ($password, $operator->getPassword ()) != 0)
        {
            $this->_fault ('operator_invalid_password');
        }

        $cashier = Mage::getModel ('pdv/cashier')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('entity_id', array ('eq' => $operator->getCashierId ()))
            ->getFirstItem ()
        ;

        if (!$cashier || !$cashier->getId ())
        {
            $this->_fault ('cashier_not_exists');
        }

        return $cashier;
    }

    public function _getOrderCollection ($cashier, $operator, $history)
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('is_pdv', array ('eq' => true))
            ->addFieldToFilter ('pdv_cashier_id', array ('eq' => $cashier->getId ()))
            ->addFieldToFilter ('pdv_operator_id', array ('eq' => $operator->getId ()))
            ->addFieldToFilter ('pdv_history_id', array ('eq' => $history->getId ()))
        ;

        if (!Mage::getStoreConfigFlag ('pdv/cashier/show_pending_orders'))
        {
             $collection->addFieldToFilter ('state', array ('in' => array (
                 Mage_Sales_Model_Order::STATE_PROCESSING,
                 Mage_Sales_Model_Order::STATE_COMPLETE,
             )));
        }

        return $collection;
    }
}

