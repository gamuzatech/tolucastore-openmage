<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Model_Chat extends Mage_Core_Model_Abstract
{
    protected function _construct ()
    {
        $this->_init ('bot/chat');
    }

    protected function _afterDelete ()
    {
        parent::_afterDelete ();

        $quote = Mage::getModel ('sales/quote')->load ($this->getQuoteId ());

        if ($quote && $quote->getId ())
        {
            $quote->delete (); // discard
        }

        $collection = Mage::getModel ('bot/message')->getCollection ()
            ->addFieldToFilter ('chat_id', array ('eq' => $this->getId ()))
        ;

        foreach ($collection as $message)
        {
            $message->delete ();
        }
    }
}

