<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Shipping_Carrier_Tablerate
    extends Mage_Shipping_Model_Carrier_Tablerate
{
    const ADMIN_WEBSITE_ID = 0;

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

    /**
     * Get Rate
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     *
     * @return Mage_Core_Model_Abstract
     */
    public function getRate(Mage_Shipping_Model_Rate_Request $request)
    {
        $websiteId = $request->getWebsiteId ();

        $destPostcode = preg_replace ('[\D]', '', $request->getDestPostcode ());

        $request->setWebsiteId (self::ADMIN_WEBSITE_ID)
            ->setDestPostcode ($destPostcode)
        ;

        $result = Mage::getResourceModel('shipping/carrier_tablerate')->getRate($request);

        $request->setWebiteId ($websiteId);

        return $result;
    }
}

