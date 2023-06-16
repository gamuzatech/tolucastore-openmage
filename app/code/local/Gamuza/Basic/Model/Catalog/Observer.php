<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Catalog_Observer extends Mage_Catalog_Model_Observer
{
    /**
     * Adds catalog categories to top menu
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCatalogToTopmenuItems (Varien_Event_Observer $observer)
    {
        $storeCategories = Mage::helper ('catalog/category')->getStoreCategories ();

        Mage::dispatchEvent ('basic_catalog_add_topmenu_items', array ('store_categories' => & $storeCategories));

        $block = $observer->getEvent ()->getBlock ();
        $block->addCacheTag (Mage_Catalog_Model_Category::CACHE_TAG);
        $this->_addCategoriesToMenu(
            $storeCategories,
            $observer->getMenu (),
            $block
        );
    }

    /**
     * Recursively adds categories to top menu
     *
     * @param Varien_Data_Tree_Node_Collection|array $categories
     * @param Varien_Data_Tree_Node $parentCategoryNode
     * @param Mage_Page_Block_Html_Topmenu $menuBlock
     * @param bool $addTags
     */
    protected function _addCategoriesToMenu($categories, $parentCategoryNode, $menuBlock, $addTags = false)
    {
        $categoryModel = Mage::getModel('catalog/category');
        foreach ($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }

            $productCollection = $category->getProductCollection();
            if (!$productCollection || !$productCollection->getSize())
            {
                $urlRewrite = Mage::getModel ('core/url_rewrite')->load ($category->getRequestPath (), 'request_path');
                if (!$urlRewrite || strpos ($urlRewrite->getOptions (), 'R') === false)
                {
                    continue;
                }
            }

            $nodeId = 'category-node-' . $category->getId();

            $categoryModel->setId($category->getId());
            if ($addTags) {
                $menuBlock->addModelTags($categoryModel);
            }

            $tree = $parentCategoryNode->getTree();
            $categoryData = array(
                'name' => $category->getName(),
                'id' => $nodeId,
                'url' => Mage::helper('catalog/category')->getCategoryUrl($category),
                'is_active' => $this->_isActiveMenuCategory($category)
            );
            $categoryNode = new Varien_Data_Tree_Node($categoryData, 'id', $tree, $parentCategoryNode);
            $parentCategoryNode->addChild($categoryNode);

            $flatHelper = Mage::helper('catalog/category_flat');
            if ($flatHelper->isEnabled() && $flatHelper->isBuilt(true)) {
                $subcategories = (array)$category->getChildrenNodes();
            } else {
                $subcategories = $category->getChildren();
            }

            $this->_addCategoriesToMenu($subcategories, $categoryNode, $menuBlock, $addTags);
        }
    }
}

