<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Catalog_Category_Tree
    extends Mage_Adminhtml_Block_Catalog_Category_Tree
{
    public function __construct()
    {
        parent::__construct();

        $this->_withProductCount = true;
    }

    protected function _prepareLayout ()
    {
        $result = parent::_prepareLayout ();

        $this->unsetChild ('store_switcher');

        return $result;
    }

    public function canAddRootCategory ()
    {
        return Mage::getStoreConfigFlag ('catalog/category/add_root_category');
    }

    public function canAddSubCategory()
    {
        $category = $this->getCategory();

        if ($category && $category->getId() && $category->getLevel() != 1)
        {
            return Mage::getStoreConfigFlag ('catalog/category/add_subcategory');
        }

        return parent::canAddSubCategory();
    }
}

