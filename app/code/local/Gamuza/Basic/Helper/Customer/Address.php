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

    public function validateCellphone($cellphone, $countryId)
    {
        $result = true;

        $phoneUtil = PhoneNumberUtil::getInstance();

        try
        {
            $phoneNumber = $phoneUtil->parse($cellphone, $countryId);

            if (!$phoneUtil->isValidNumber($phoneNumber))
            {
                $result = false;
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
            $result = false;
        }

        return $result;
    }
}

