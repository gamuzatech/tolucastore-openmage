<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Usa
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Fedex packaging source implementation
 *
 * @category   Mage
 * @package    Mage_Usa
 */
class Mage_Usa_Model_Shipping_Carrier_Fedex_Source_Unitofmeasure
{
    /**
     * Return array of Measure units
     *
     * @return array
     */
    public function toOptionArray()
    {
        $measureUnits = Mage::getSingleton('usa/shipping_carrier_fedex')->getCode('unit_of_measure');
        $result = [];
        foreach ($measureUnits as $key => $val) {
            $result[] = ['value' => $key,'label' => $val];
        }
        return $result;
    }
}
