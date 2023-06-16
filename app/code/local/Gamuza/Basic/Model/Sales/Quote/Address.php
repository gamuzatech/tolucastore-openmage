<?php
/**
 * @package     Gamuza_Store
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Sales Quote address model
 */
class Gamuza_Basic_Model_Sales_Quote_Address extends Mage_Sales_Model_Quote_Address
{
    /**
     * Perform basic validation
     *
     * @return void
     */
    protected function _basicCheck()
    {
        if (!Zend_Validate::is($this->getFirstname(), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the first name.'));
        }

        if (!Zend_Validate::is($this->getLastname(), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the last name.'));
        }

        if (!Zend_Validate::is($this->getStreet(1), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the street.'));
        }

        if (!Zend_Validate::is($this->getCity(), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the city.'));
        }

        if (!Zend_Validate::is($this->getCellphone(), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the cellphone number.'));
        }

        $_havingOptionalZip = Mage::helper('directory')->getCountriesWithOptionalZip();

        if (!in_array($this->getCountryId(), $_havingOptionalZip)
            && !Zend_Validate::is($this->getPostcode(), 'NotEmpty')
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the zip/postal code.'));
        }

        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty'))
        {
            $this->addError(Mage::helper('customer')->__('Please enter the country.'));
        }

        if ($this->getCountryModel()->getRegionCollection()->getSize()
            && !Zend_Validate::is($this->getRegionId(), 'NotEmpty')
            && Mage::helper('directory')->isRegionRequired($this->getCountryId())
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the state/province.'));
        }
    }
}

