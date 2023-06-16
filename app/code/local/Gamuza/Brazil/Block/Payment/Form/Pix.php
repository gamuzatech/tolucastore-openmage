<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Payment_Form_Pix extends Mage_Payment_Block_Form
{
    /**
     * Instructions text
     *
     * @var string
     */
    protected $_instructions;

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/brazil/payment/form/pix.phtml');
    }

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        if (is_null($this->_instructions))
        {
            $this->_instructions = $this->getMethod()->getInstructions();
        }

        return $this->_instructions;
    }
}

