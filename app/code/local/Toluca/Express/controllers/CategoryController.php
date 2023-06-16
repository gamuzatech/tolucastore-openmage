<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Catalog') . DS . 'CategoryController.php');

/**
 * Express controller
 */
class Toluca_Express_CategoryController extends Mage_Catalog_CategoryController
{
    public const LAYOUT_UPDATE_HANDLE = 'toluca_express_category_layered';

    public const TREE_ROOT_ID = Mage_Catalog_Model_Category::TREE_ROOT_ID;

    public function renderLayout($output = '')
    {
        $this->getLayout()->getBlock('head')->setTitle(
            sprintf('%s - %s',
                Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME),
                $this->__('Express')
            )
        );

        Mage::getConfig ()->setNode (
            Mage_Catalog_Model_Factory::XML_PATH_PRODUCT_URL_MODEL, 'express/catalog_product_url'
        );

        return parent::renderLayout($output);
    }

    /**
     * Initialize requested category object
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _initCategory()
    {
        if (!Mage::getStoreConfigFlag(Toluca_Express_Helper_Data::XML_PATH_EXPRESS_SETTING_ACTIVE))
        {
            return false;
        }

        Mage::dispatchEvent('catalog_controller_category_init_before', array('controller_action' => $this));

        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();

        $categoryId = (int) $this->getRequest()->getParam('cat', false);

        if ($categoryId && in_array($categoryId, array($rootCategoryId, self::TREE_ROOT_ID)))
        {
            Mage::app()->getRequest()->setRequestUri('/express');
            Mage::app()->getRequest()->setParam('cat', $rootCategoryId = $categoryId);
        }

        $category = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($rootCategoryId)
        ;

        if (!$category || !$category->getId() || !$category->getIsActive())
        {
            return false;
        }
        else
        {
            $category->setName(null);
            $category->setLayoutCustomHandle(self::LAYOUT_UPDATE_HANDLE);
        }

/*
        if (!Mage::helper('catalog/category')->canShow($category))
        {
            return false;
        }
*/

        Mage::getSingleton('catalog/session')->setLastVisitedCategoryId($category->getId());

        Mage::register('current_category', $category);
        Mage::register('current_entity_key', $category->getPath());

        try
        {
            Mage::dispatchEvent(
                'catalog_controller_category_init_after',
                array(
                    'category' => $category,
                    'controller_action' => $this
                )
            );
        }
        catch (Mage_Core_Exception $e)
        {
            Mage::logException($e);

            return false;
        }

        return $category;
    }
}

