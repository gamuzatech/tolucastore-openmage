<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Sales_Quote_Address_Total_Shipping
    extends Mage_Sales_Model_Quote_Address_Total_Shipping
    // extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /**
     * Add shipping totals information to address object
     *
     * @return $this
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getShippingAmount();

        if ($amount != 0 || $address->getShippingDescription())
        {
            $title = Mage::helper('sales')->__('Shipping & Handling');

            if ($address->getShippingDescription())
            {
                $title = $address->getShippingDescription();
            }

            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => $title,
                'value' => $address->getShippingAmount(),
            ));
        }

        return $this;
    }
}

