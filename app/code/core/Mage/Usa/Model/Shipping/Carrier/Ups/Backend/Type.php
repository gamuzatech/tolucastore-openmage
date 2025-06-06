<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Usa
 */

/**
 * Backend model for validate ups type field
 *
 * @package    Mage_Usa
 */
class Mage_Usa_Model_Shipping_Carrier_Ups_Backend_Type extends Mage_Usa_Model_Shipping_Carrier_Abstract_Backend_Abstract
{
    /**
     * Set source model to get allowed values
     */
    protected function _setSourceModelData()
    {
        $this->_sourceModel = 'usa/shipping_carrier_ups_source_type';
    }

    /**
     * Set field name to display in error block
     */
    protected function _setNameErrorField()
    {
        $this->_nameErrorField = 'UPS Type';
    }
}
