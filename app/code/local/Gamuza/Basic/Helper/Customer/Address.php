<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberType;
use libphonenumber\NumberParseException;

/**
 * Customer address helper
 */
class Gamuza_Basic_Helper_Customer_Address extends Mage_Customer_Helper_Address
{
    /**
     * Code Area + Phone Number
     */
    const CELLPHONE_LENGTH_MINIMUM = 10;

    const CELLPHONE_DEFAULT_NUMBER = '000000000';

    const CELLPHONE_BYPASS_NUMBER = '999999999';

    public function validateCellphone($cellphone, $countryId)
    {
        $result = true;

        $phoneUtil = PhoneNumberUtil::getInstance();

        try
        {
            $cellphone = $cellphone ?? self::CELLPHONE_DEFAULT_NUMBER;

            $phoneNumber = $phoneUtil->parse($cellphone, $countryId);

            if (!$phoneUtil->isValidNumber($phoneNumber))
            {
                if (!str_ends_with ($cellphone, self::CELLPHONE_BYPASS_NUMBER))
                {
                    $result = false;
                }

                $possibleCellphone = Mage::getStoreConfigFlag(Gamuza_Basic_Model_Customer_Customer::XML_PATH_POSSIBLE_CUSTOMER_CELLPHONE);

                if ($phoneUtil->isPossibleNumber($phoneNumber) && $possibleCellphone)
                {
                    $result = true;
                }
            }
            else
            {
                $nationalNumber = $phoneNumber->getNationalNumber();

                $numberType = $phoneUtil->getNumberType($phoneNumber);

                if ($numberType != PhoneNumberType::MOBILE)
                {
                    $result = false;
                }

                if (strlen ($nationalNumber) < self::CELLPHONE_LENGTH_MINIMUM)
                {
                    $result = false;
                }
            }
        }
        catch (NumberParseException $e)
        {
            Mage::log ($e->getMessage (), null, 'cellphone.log');

            $result = false;
        }

        return $result;
    }
}

