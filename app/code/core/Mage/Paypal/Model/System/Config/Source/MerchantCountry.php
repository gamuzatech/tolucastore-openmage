<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Paypal
 */

/**
 * Source model for merchant countries supported by PayPal
 *
 * @package    Mage_Paypal
 */
class Mage_Paypal_Model_System_Config_Source_MerchantCountry
{
    public function toOptionArray($isMultiselect = false)
    {
        $supported = Mage::getModel('paypal/config')->getSupportedMerchantCountryCodes();
        return Mage::getResourceModel('directory/country_collection')
            ->addCountryCodeFilter($supported, 'iso2')
            ->loadData()
            ->toOptionArray($isMultiselect ? false : Mage::helper('adminhtml')->__('--Please Select--'));
    }
}
