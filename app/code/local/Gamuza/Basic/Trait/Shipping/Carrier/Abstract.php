<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

trait Gamuza_Basic_Trait_Shipping_Carrier_Abstract
{
    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return false|Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active'))
        {
            return false;
        }

        $attributeSets = $this->getConfigData('attribute_set');

        if (!empty($attributeSets))
        {
            $attributeSets = explode(',', $attributeSets);

            $itemsCount = 0;

            foreach ($request->getAllItems() as $item)
            {
                if (!in_array($item->getProduct()->getAttributeSetId(), $attributeSets))
                {
                    $itemsCount ++;
                }
            }

            if ($itemsCount > 0 && $this->getConfigFlag('showmethod'))
            {
                $error = Mage::getModel('shipping/rate_result_error')
                    ->setCarrier($this->_code)
                    ->setCarrierTitle($this->getConfigData('title'))
                    ->setErrorMessage($this->getConfigData('specificerrmsg')
                        ?: Mage::helper('shipping')->__('The shipping module is not available for selected delivery country.'))
                ;

                return $error;
            }
            else if ($itemsCount > 0)
            {
                return false;
            }
        }

        return parent::collectRates($request);
    }
}

