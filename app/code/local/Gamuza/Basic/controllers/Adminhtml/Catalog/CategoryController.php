<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Catalog' . DS . 'CategoryController.php');

class Gamuza_Basic_Adminhtml_Catalog_CategoryController
    extends Mage_Adminhtml_Catalog_CategoryController
{
    /**
     * Catalog categories index action
     */
    public function indexAction()
    {
        $rootCategoryId = Mage::app()
            ->getStore(Mage_Core_Model_App::DISTRO_STORE_ID)
            ->getRootCategoryId ()
        ;

        $this->_forward('edit', null, null, array ('id' => $rootCategoryId, 'clear' => true));
    }
}

