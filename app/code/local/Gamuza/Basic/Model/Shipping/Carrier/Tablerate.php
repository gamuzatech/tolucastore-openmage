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

