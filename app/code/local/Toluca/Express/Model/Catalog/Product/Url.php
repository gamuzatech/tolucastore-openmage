<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product Url model
 */
class Toluca_Express_Model_Catalog_Product_Url
    extends Mage_Catalog_Model_Product_Url
{
    /**
     * Retrieve product URL based on requestPath param
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $requestPath
     * @param array $routeParams
     *
     * @return string
     */
    protected function _getProductUrl($product, $requestPath, $routeParams)
    {
        $routeParams['id'] = $product->getId();
        $routeParams['s'] = $product->getUrlKey();

        return $this->getUrlInstance()->getUrl('express/product/view', $routeParams);
    }
}

