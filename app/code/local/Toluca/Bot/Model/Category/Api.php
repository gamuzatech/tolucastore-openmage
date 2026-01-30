<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Category API
 */
class Toluca_Bot_Model_Category_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ()
    {
        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $result = $this->_getCategoryList ($storeId);

        if ($result != null)
        {
            $result = Mage::helper ('bot/message')->getCategoriesListText () . PHP_EOL . PHP_EOL . $result;
        }

        return $result;
    }
}

