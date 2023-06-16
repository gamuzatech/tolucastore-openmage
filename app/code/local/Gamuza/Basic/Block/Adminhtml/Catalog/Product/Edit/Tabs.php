<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * admin product edit tabs
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tabs
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    const CATALOG_PRODUCT_GROUP_ID_9  = 'group_9';  // meta_information
    const CATALOG_PRODUCT_GROUP_ID_11 = 'group_11'; // recurring_profile
    const CATALOG_PRODUCT_GROUP_ID_12 = 'group_12'; // custom_design
    const CATALOG_PRODUCT_GROUP_ID_17 = 'group_17'; // gift_message

    protected function _prepareLayout ()
    {
        $result = parent::_prepareLayout ();

        $this->removeTab (self::CATALOG_PRODUCT_GROUP_ID_9);
        $this->removeTab (self::CATALOG_PRODUCT_GROUP_ID_11);
        $this->removeTab (self::CATALOG_PRODUCT_GROUP_ID_12);
        $this->removeTab (self::CATALOG_PRODUCT_GROUP_ID_17);

        /*
        $this->removeTab ('related');
        $this->removeTab ('upsell');
        $this->removeTab ('crosssell');
        */

        return $result;
    }
}

