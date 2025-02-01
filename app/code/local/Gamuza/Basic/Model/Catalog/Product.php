<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product model
 */
class Gamuza_Basic_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
    /**
     * Retrieve collection raw material product
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Product_Collection
     */
    public function getMaterialProductCollection()
    {
        $collection = $this->getLinkInstance()->useMaterialLinks()
            ->getProductCollection()
            ->setIsStrongMode();

        $collection->setProduct($this);

        return $collection;
    }

    /**
     * Retrieve array of raw material roducts
     *
     * @return Mage_Catalog_Model_Product[]
     */
    public function getMaterialProducts()
    {
        if (!$this->hasMaterialProducts())
        {
            $products = [];

            $collection = $this->getMaterialProductCollection();

            foreach ($collection as $product)
            {
                $products[] = $product;
            }

            $this->setMaterialProducts($products);
        }

        return $this->getData('material_products');
    }

   /**
     * Retrieve raw material products identifiers
     *
     * @return array
     */
    public function getMaterialProductIds()
    {
        if (!$this->hasMaterialProductIds())
        {
            $ids = [];

            foreach ($this->getMaterialProducts() as $product)
            {
                $ids[] = $product->getId();
            }

            $this->setMaterialProductIds($ids);
        }

        return $this->getData('material_product_ids');
    }
}

