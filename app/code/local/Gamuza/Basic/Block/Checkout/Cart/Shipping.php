<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Checkout_Cart_Shipping
    extends Mage_Checkout_Block_Cart_Shipping
{
    /**
     * Get Estimate Region Id
     *
     * @return mixed
     */
    public function getEstimateRegionId()
    {
        return $this->getAddress()->getRegionId()
            ?? Mage::getStoreconfig(Mage_Shipping_Model_Config::XML_PATH_ORIGIN_REGION_ID);
    }
}

