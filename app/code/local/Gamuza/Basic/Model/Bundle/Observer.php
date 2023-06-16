<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle Products Observer
 */
class Gamuza_Basic_Model_Bundle_Observer extends Mage_Bundle_Model_Observer
{
    /**
     * Setting Bundle Items Data to product for father processing
     *
     * @param Varien_Object $observer
     * @return Mage_Bundle_Model_Observer
     */
    public function prepareProductSave($observer)
    {
        $request = $observer->getEvent()->getRequest();
        $product = $observer->getEvent()->getProduct();

        if (($items = $request->getPost('bundle_options')) && !$product->getCompositeReadonly()) {
            $product->setBundleOptionsData($items);
        }

        if (($selections = $request->getPost('bundle_selections')) && !$product->getCompositeReadonly()) {
            $product->setBundleSelectionsData($selections);
        }
/*
        if ($product->getPriceType() == '0' && !$product->getOptionsReadonly()) {
            $product->setCanSaveCustomOptions(true);
            if ($customOptions = $product->getProductOptions()) {
                foreach (array_keys($customOptions) as $key) {
                    $customOptions[$key]['is_delete'] = 1;
                }
                $product->setProductOptions($customOptions);
            }
        }
*/
        $product->setCanSaveBundleSelections(
            (bool)$request->getPost('affect_bundle_product_selections') && !$product->getCompositeReadonly()
        );

        return $this;
    }
}

