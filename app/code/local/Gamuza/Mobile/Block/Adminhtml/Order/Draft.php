<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Block_Adminhtml_Order_Draft extends Mage_Adminhtml_Block_Template
{
    private $_wordwrap = 48; // default

    public function __construct ()
    {
        parent::__construct ();

        $this->_wordwrap = intval (Mage::getStoreConfig ('sales/order_draft/word_wrap'));
    }

    public function getWordWrap ()
    {
        return $this->_wordwrap;
    }

    public function getLineSeparator ($title = null)
    {
        $result = str_repeat ('-', $this->_wordwrap);

        if (empty ($title))
        {
            return $result;
        }

        $title = sprintf (' %s ', $title);

        $position = strlen ($result) / 2 - strlen ($title) / 2;

        for ($i = 0; $i < strlen ($title); $i ++)
        {
            $result [$i + $position] = $title [$i];
        }

        return $result;
    }

    public function getOrderNumber ()
    {
        return sprintf ('%s-%s', $this->getOrder()->getRealOrderId (), $this->getOrder()->getProtectCode ());
    }

    public function getOrderDatetime ()
    {
        return Mage::helper('core')->formatDate($this->getOrder ()->getCreatedAtDate(), 'medium', true);
    }

    public function getOrderSequence ()
    {
        $result = 0;

        if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV'))
        {
            $result = $this->getOrder ()->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_SEQUENCE_ID);
        }

        return $result;
    }
}

