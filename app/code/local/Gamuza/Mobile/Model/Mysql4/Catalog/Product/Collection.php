<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product collection
 */
class Gamuza_Mobile_Model_Mysql4_Catalog_Product_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * Adding product custom options to result collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addOptionsToResult()
    {
        $productIds = array();

        foreach ($this as $product)
        {
            $productIds[] = $product->getId();
        }

        if (!empty($productIds))
        {
            $options = Mage::getModel('catalog/product_option')
                ->getCollection()
                ->addTitleToResult(Mage::app()->getStore()->getId())
                ->addPriceToResult(Mage::app()->getStore()->getId())
                ->addProductToFilter($productIds)
                // ->addValuesToResult()
            ;

            $options->getSelect()->order('sort_order ASC');

            $options->addValuesToResult();

            foreach ($options as $option)
            {
                if($this->getItemById($option->getProductId()))
                {
                    $this->getItemById($option->getProductId())->addOption($option);
                }
            }
        }

        return $this;
    }
}

