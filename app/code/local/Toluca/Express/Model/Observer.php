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

        $category = Mage::getModel ('catalog/category')
            ->setIsActive (true)
            ->setName ('Express')
            ->setUrl ('/express');

        array_unshift ($storeCategories, $category);

        $event->setStoreCategories ($storeCategories);

        return $this;
    }
}

