<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Checkout_Session extends Mage_Checkout_Model_Session
{
    /**
     * Get checkout quote instance by current session
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        parent::getQuote();

        $userAgent = Mage::helper ('basic')->getUserAgent ();

        if (!empty ($userAgent))
        {
            $this->_quote->setUserAgent ($userAgent);
        }

        return $this->_quote;
    }
}


