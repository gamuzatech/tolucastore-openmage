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
    const XML_PATH_PDV_CASHIER_SHOW_OPERATOR_ORDERS = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_SHOW_OPERATOR_ORDERS;
    const XML_PATH_PDV_CASHIER_SHOW_PENDING_ORDERS = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_SHOW_PENDING_ORDERS;
    const XML_PATH_PDV_CASHIER_VALIDATE_REMOTE_IP = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_VALIDATE_REMOTE_IP;
    const XML_PATH_PDV_CASHIER_ALLOW_NEGATIVE_FLOW = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_ALLOW_NEGATIVE_FLOW;
    const XML_PATH_PDV_CASHIER_ALLOW_BROKEN_FLOW = Toluca_PDV_Helper_Data::XML_PATH_PDV_CASHIER_ALLOW_BROKEN_FLOW;

    public $_validateRemoteIp = false;
    public $_allowNegativeFlow = false;
    public $_allowBrokenFlow = false;

    public function __construct()
    {
        // parent::__construct();

        $this->_validateRemoteIp = Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_VALIDATE_REMOTE_IP);
        $this->_allowNegativeFlow = Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_ALLOW_NEGATIVE_FLOW);
        $this->_allowBrokenFlow = Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_ALLOW_BROKEN_FLOW);
    }

    public function info ($cashier_id, $operator_id)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        if (empty ($operator_id))
        {
            $this->_fault ('operator_not_specified');
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
            ->addFieldToFilter ('is_active',  array ('eq' => true))
            ->addFieldToFilter ('entity_id',  array ('eq' => $operator_id))
            ->addFieldToFilter ('cashier_id', array ('eq' => $cashier->getId ()))
            ->getFirstItem ()
        ;

        if (!$operator || !$operator->getId ())
        {
            $this->_fault ('operator_not_exists');
        }

        $result = array(
            'entity_id'  => intval ($cashier->getId ()),
            'code'       => $cashier->getCode (),
            'name'       => $cashier->getName (),
            'is_active'  => boolval ($cashier->getIsActive ()),
            'status'     => intval ($cashier->getStatus ()),
            'operator_id'   => intval ($operator_id),
            'operator_code' => $operator->getCode (),
            'operator_name' => $operator->getName (),
            'history_id' => intval ($cashier->getHistoryId ()),
            'created_at' => $cashier->getCreatedAt (),
            'updated_at' => $cashier->getUpdatedAt (),
            'order_amount' => floatval ($cashier->getOrderAmount ()),
            'customer_id' => intval ($operator->getCustomerId ()),
            'quote_id'    => intval ($operator->getQuoteId ()),
            'table_id'    => intval ($operator->getTableId ()),
            'card_id '    => intval ($operator->getCardId ()),
            'opened_at' => $cashier->getOpenedAt (),
            'closed_at' => $cashier->getClosedAt (),
            'remote_ip' => $cashier->getRemoteIp (),
            'user_agent' => $cashier->getUserAgent (),
            'history' => $cashier->getHistory (),
        );

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
                'subtotal_amount' => floatval ($history->getSubtotalAmount ()),
                'refund_amount'   => floatval ($history->getRefundAmount ()),
                'shipping_amount' => floatval ($history->getShippingAmount ()),
                'total_amount'    => floatval ($history->getTotalAmount ()),
                'created_at' => $history->getCreatedAt (),
                'updated_at' => $history->getUpdatedAt (),
                'remote_ip' => $history->getRemoteIp (),
                'user_agent' => $history->getUserAgent (),
            );

            $backup = Mage::getSingleton ('backup/fs_collection')
                ->addFieldTofilter ('filename', array ('notnull' => true))
                ->addFieldTofilter ('basename', array ('notnull' => true))
                ->addFieldTofilter ('path', array ('notnull' => true))
                ->addFieldTofilter ('id', array ('notnull' => true))
                ->addFieldTofilter ('extension', array ('eq' => 'gz'))
                ->addFieldTofilter ('type', array ('eq' => 'db'))
                ->addFieldTofilter ('time', array ('gt' => 0))
                ->addFieldTofilter ('size', array ('gt' => 0))
                ->setPageSize (2)
                ->getFirstItem ()
            ;

            if ($backup && $backup->getId ())
            {
                $result ['backup'] = array(
                    'filename'     => $backup->getFilename (),
                    'basename'     => $backup->getBasename (),
                    'id'           => $backup->getId (),
                    'time'         => intval ($backup->getTime ()),
                    'time_at'      => date ('c', $backup->getTime ()),
                    'path'         => $backup->getPath (),
                    'extension'    => $backup->getExtension (),
                    'display_name' => $backup->getDisplayName (),
                    'name'         => $backup->getName (),
                    'type'         => $backup->getType (),
                    'size'         => intval ($backup->getSize ()),
                    'hash'         => hash_file ('sha256', $backup->getFilename ()),
                    'last'         => Mage::getStoreConfig ('system/backup/last'),
                    'is_gzip'      => Mage::helper ('pdv')->isGzip ($backup->getFilename ())
                );
            }
        }

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('entity_id', array ('eq' => $operator->getQuoteId ()))
            ->addFieldToFilter ('pdv_customer_id', array ('eq' => $operator->getCustomerId ()))
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

    public function draft ($cashier_id, $operator_id, $operation = null)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        if (empty ($operator_id))
        {
            $this->_fault ('operator_not_specified');
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
            ->addFieldToFilter ('is_active',  array ('eq' => true))
            ->addFieldToFilter ('entity_id',  array ('eq' => $operator_id))
            ->addFieldToFilter ('cashier_id', array ('eq' => $cashier->getId ()))
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

        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        $print = Mage::getModel ('pdv/print')
            ->setTypeId (Toluca_PDV_Helper_Data::PRINT_TYPE_CASHIER)
            ->setCustomerId ($operator->getCustomerId ())
            ->setQuoteId ($operator->getQuoteId ())
            ->setHistoryId ($history->getId ())
            ->setSequenceId ($cashier->getSequenceId ())
            ->setTableId ($operator->getTableId ())
            ->setCardId ($operator->getCardId ())
            ->setCashierId ($cashier->getId ())
            ->setOperatorId ($operator->getId ())
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

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

    public function clear ($cashier_id, $operator_id)
    {
        if (empty ($cashier_id))
        {
            $this->_fault ('cashier_not_specified');
        }

        if (empty ($operator_id))
        {
            $this->_fault ('operator_not_specified');
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
            ->addFieldToFilter ('is_active',  array ('eq' => true))
            ->addFieldToFilter ('entity_id',  array ('eq' => $operator_id))
            ->addFieldToFilter ('cashier_id', array ('eq' => $cashier->getId ()))
            ->getFirstItem ()
        ;

        if (!$operator || !$operator->getId ())
        {
            $this->_fault ('operator_not_exists');
        }

        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        $operator->setQuoteId (0)
            ->setCustomerId (0)
            ->setTableId (0)
            ->setCardId (0)
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        return true;
    }

    public function open ($operator_id, $password, $amount, $message)
    {
        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        list ($cashier, $operator) = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getRemoteIp () && $cashier->getRemoteIp () != $remoteIp && $this->_validateRemoteIp)
        {
            $this->_fault ('data_invalid');
        }

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
            ->setSubtotalAmount (0)
            ->setRefundAmount (0)
            ->setShippingAmount (0)
            ->setTotalAmount (0)
            ->setCreatedAt (date ('c'))
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        $cashier->setStatus (Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED)
            ->setHistoryId ($history->getId ())
            ->setSequenceId (0)
            ->setOpenedAt (date ('c'))
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        $operator->setQuoteId (0)
            ->setCustomerId (0)
            ->setTableId (0)
            ->setCardId (0)
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
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
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function reinforce ($operator_id, $password, $amount, $message)
    {
        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        list ($cashier, $operator) = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getRemoteIp () && $cashier->getRemoteIp () != $remoteIp && $this->_validateRemoteIp)
        {
            $this->_fault ('data_invalid');
        }

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
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
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
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function bleed ($operator_id, $password, $amount, $message)
    {
        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        list ($cashier, $operator) = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getRemoteIp () && $cashier->getRemoteIp () != $remoteIp && $this->_validateRemoteIp)
        {
            $this->_fault ('data_invalid');
        }

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

        $refundAmount = floatval ($history->getRefundAmount ());

        $closeAmount = ((($openAmount + $reinforceAmount) + $bleedAmount) + $moneyAmount) + $changeAmount
            + $openpixAmount + $checkAmount + $pixAmount
            + $refundAmount;

        if ($amount > $closeAmount && !$this->_allowNegativeFlow)
        {
            $closeAmount = Mage::helper ('core')->currency ($closeAmount, true, false);

            $message = Mage::helper ('pdv')->__('Bleed amount is invalid. Allowed only %s.', $closeAmount);

            $this->_fault ('cashier_invalid_amount', $message);
        }

        $history->setBleedAmount ($bleedAmount + (- $amount))
            ->setUpdatedAt (date ('c'))
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
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
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        return intval ($cashier->getId ());
    }

    public function close ($operator_id, $password, $amount, $message)
    {
        $remoteIp = Mage::helper ('pdv')->getRemoteIp ();
        $userAgent = Mage::helper ('pdv')->getUserAgent ();

        list ($cashier, $operator) = $this->_getCashier ($operator_id, $password, $amount);

        if ($cashier->getRemoteIp () && $cashier->getRemoteIp () != $remoteIp && $this->_validateRemoteIp)
        {
            $this->_fault ('data_invalid');
        }

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
            $refundAmount       = floatval ($history->getRefundAmount ());

            $orderAmount = $machineAmount
                + $pagcriptoAmount + $picpayAmount + $openpixAmount
                + $creditcardAmount + $billetAmount + $banktransferAmount + $checkAmount + $pixAmount
                + $refundAmount
            ;
        }

        $differenceAmount = round ($amount - ($closeAmount + $orderAmount), 4);

        if ($differenceAmount != 0 && !$this->_allowBrokenFlow)
        {
            $differenceAmount = Mage::helper ('core')->currency ($differenceAmount, true, false);

            $message = Mage::helper ('pdv')->__('Close amount is invalid. Difference of %s.', $differenceAmount);

            $this->_fault ('cashier_invalid_amount', $message);
        }

        $history->setCloseAmount (- $amount)
            ->setClosedAt (date ('c'))
            ->setUpdatedAt (date ('c'))
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
            ->save ()
        ;

        $cashier->setStatus (Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED)
            ->setClosedAt (date ('c'))
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
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
            ->setRemoteIp ($remoteIp)
            ->setUserAgent ($userAgent)
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

        return array ($cashier, $operator);
    }

    public function _getOrderCollection ($cashier, $operator, $history)
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('is_pdv', array ('eq' => true))
            ->addFieldToFilter ('pdv_cashier_id', array ('eq' => $cashier->getId ()))
            ->addFieldToFilter ('pdv_history_id', array ('eq' => $history->getId ()))
        ;

        if (!Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_SHOW_OPERATOR_ORDERS))
        {
            $collection->addFieldToFilter ('pdv_operator_id', array ('eq' => $operator->getId ()));
        }

        if (!Mage::getStoreConfigFlag (self::XML_PATH_PDV_CASHIER_SHOW_PENDING_ORDERS))
        {
             $collection->addFieldToFilter ('state', array ('in' => array (
                 Mage_Sales_Model_Order::STATE_PROCESSING,
                 Mage_Sales_Model_Order::STATE_COMPLETE,
             )));
        }

        return $collection;
    }
}

