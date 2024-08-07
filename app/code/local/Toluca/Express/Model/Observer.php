<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Express module observer
 */
class Toluca_Express_Model_Observer
{
    public function catalogAddTopmenuItems ($observer)
    {
        if (!Mage::getStoreConfigFlag (Toluca_Express_Helper_Data::XML_PATH_EXPRESS_SETTING_ACTIVE))
        {
            return $this;
        }

        $event = $observer->getEvent ();
        $storeCategories = $event->getStoreCategories ();
        $menu = $event->getMenu ();

        $category = new Varien_Data_Tree_Node (array(
            'id'        => PHP_INT_MAX,
            'is_active' => true,
            'name'      => 'Delivery',
            'url'       => '/delivery',
        ), 'id', $menu->getTree (), $storeCategories);

        $menu->addChild ($category);

        $event->setStoreCategories ($storeCategories);

        return $this;
    }
}

