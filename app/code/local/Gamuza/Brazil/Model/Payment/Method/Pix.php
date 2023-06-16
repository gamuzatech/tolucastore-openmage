<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Payment_Method_Pix extends Mage_Payment_Model_Method_Abstract
{
    const CODE = 'gamuza_brazil_pix';

    protected $_code = self::CODE;

    protected $_formBlockType = 'brazil/payment_form_pix';
    protected $_infoBlockType = 'brazil/payment_info_pix';

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }

    public function isApplicableToQuote($quote, $checksBitMask)
    {
        return Mage_Payment_Model_Method_Abstract::isApplicableToQuote($quote, $checksBitMask);
    }
}

