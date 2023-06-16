<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Category View block
 */
class Toluca_Express_Block_Catalog_Category_View
    extends Mage_Catalog_Block_Category_View
{
    const DEFAULT_COLUMN_COUNT = 6;
    /**
     * @return string
     */
    public function _getProductListHtml($category)
    {
        return $this->getLayout ()
            ->createBlock ('express/catalog_product_list')
            ->setCategoryId ($category->getId ())
            ->setColumnCount (self::DEFAULT_COLUMN_COUNT)
            ->setTemplate ('toluca/express/catalog/product/list.phtml')
            ->toHtml ();
    }
}

