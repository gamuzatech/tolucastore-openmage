<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

/**
 * Address abstract model
 */
class Gamuza_Basic_Model_Customer_Address extends Mage_Customer_Model_Address
{
    const CELLPHONE_LENGTH_MINIMUM = 10;

    /**
     * Return Region ID
     *
     * @return int
     */
    public function getRegionId()
    {
        return Mage_Customer_Model_Address_Abstract::getRegionId();
    }

    /**
     * Set Region ID. $regionId is automatically converted to integer
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId)
    {
        return Mage_Customer_Model_Address_Abstract::setRegionId($regionId);
    }

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

        $phoneUtil  = PhoneNumberUtil::getInstance();
        $phoneError = false;

        try
        {
            $phoneNumber = $phoneUtil->parse($this->getCellphone(), $this->getCountryId());

            if (!$phoneUtil->isValidNumber($phoneNumber))
            {
                $phoneError = true;
            }
            else
            {
                $nationalNumber = $phoneNumber->getNationalNumber();

                if (strlen ($nationalNumber) < self::CELLPHONE_LENGTH_MINIMUM)
                {
                    $phoneError = true;
                }
            }
        }
        catch (NumberParseException $e)
        {
            $phoneError = true;
        }

        if ($phoneError)
        {
            $this->addError(Mage::helper('customer')->__('Invalid cellphone number for region %s.', $this->getCountryId()));
        }
    }
}

