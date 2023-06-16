<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product category
 */
class Gamuza_Basic_Block_Catalog_Product_Category
    extends Mage_Catalog_Block_Product_Abstract
{
    public function getCategoryCollection ()
    {
        $categories = array ();

        $collection = Mage::getModel ('catalog/category')->getCollection ()
            ->addNameToResult ()
            ->addIsActiveFilter ()
            ->addAttributeToFilter ('is_anchor', array ('eq' => true))
            ->addAttributeToFilter ('include_in_menu', array ('eq' => true))
            ->addAttributeToFilter ('include_in_home', array ('eq' => true))
        ;

        foreach ($collection as $category)
        {
            if ($category->hasChildren ())
            {
                foreach ($category->getChildrenCategories () as $child)
                {
                    $categories [$child->getId ()] = $child;
                }
            }
            else
            {
                $categories [$category->getId ()] = $category;
            }
        }

        return $categories;
    }

    public function _getProductHtml ($category)
    {
        return $this->getLayout ()
            ->createBlock ('basic/catalog_product_list_category')
            ->setCategory ($category)
            ->setTemplate ('gamuza/basic/catalog/product/list/category.phtml')
            ->toHtml ();
    }
}

