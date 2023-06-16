<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Block_Payment_Form_Payment extends Mage_Payment_Block_Form
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

        $this->setTemplate('gamuza/pagcripto/payment/form/payment.phtml');
    }

    protected function _getConfig ()
    {
        return Mage::getSingleton ('pagcripto/payment_config');
    }

    public function getCcAvailableTypes ()
    {
        $types = $this->_getConfig ()->getCcTypes ();

        if ($method = $this->getMethod ())
        {
            $availableTypes = $method->getConfigData ('cctypes');

            if ($availableTypes)
            {
                $availableTypes = explode (',', $availableTypes);

                foreach ($types as $code => $name)
                {
                    if (!in_array ($code, $availableTypes))
                    {
                        unset ($types [$code]);
                    }
                }
            }
        }

        return $types;
    }

    public function getSkinUrl ($file = null, array $params = array ())
    {
        return parent::getSkinUrl (Gamuza_PagCripto_Helper_Data::PAYMENT_IMAGE_PREFIX . $file);
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

