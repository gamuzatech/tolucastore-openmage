<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Helper for fetching properties by product configurational item
 */
class Gamuza_Basic_Helper_Bundle_Catalog_Product_Configuration
    extends Mage_Bundle_Helper_Catalog_Product_Configuration
{
    /**
     * Obtain final price of selection in a bundle product
     *
     * @param Mage_Catalog_Model_Product_Configuration_Item_Interface $item
     * @param Mage_Catalog_Model_Product $selectionProduct
     * @return float
     */
    public function getSelectionFinalPrice(
        Mage_Catalog_Model_Product_Configuration_Item_Interface $item,
        $selectionProduct
    ) {
        if (Mage::getStoreConfigFlag (Gamuza_Basic_Helper_Data::XML_PATH_CATALOG_PRODUCT_BUNDLE_OPTION_SELECT_PRICE))
        {
            $result = parent::getSelectionFinalPrice ($item, $selectionProduct);

            return Mage::helper ('core')->currency ($result);
        }
    }

    /**
     * Get bundled selections (slections-products collection)
     *
     * Returns array of options objects.
     * Each option object will contain array of selections objects
     *
     * @param Mage_Catalog_Model_Product_Configuration_Item_Interface $item
     * @return array
     */
    public function getBundleOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item)
    {
        $options = [];

        $product = $item->getProduct();

        /** @var Mage_Bundle_Model_Product_Type $typeInstance */
        $typeInstance = $product->getTypeInstance(true);

        // get bundle options
        $optionsQuoteItemOption = $item->getOptionByCode('bundle_option_ids');

        $bundleOptionsIds = $optionsQuoteItemOption ? unserialize($optionsQuoteItemOption->getValue(), ['allowed_classes' => false]) : [];

        if ($bundleOptionsIds)
        {
            $optionsCollection = $typeInstance->getOptionsByIds($bundleOptionsIds, $product);

            // get and add bundle selections collection
            $selectionsQuoteItemOption = $item->getOptionByCode('bundle_selection_ids');

            $bundleSelectionIds = unserialize($selectionsQuoteItemOption->getValue(), ['allowed_classes' => false]);

            if (!empty($bundleSelectionIds))
            {
                $selectionsCollection = $typeInstance->getSelectionsByIds(
                    unserialize($selectionsQuoteItemOption->getValue(), ['allowed_classes' => false]),
                    $product
                );

                /** @var Mage_Bundle_Model_Option[] $bundleOptions */
                $bundleOptions = $optionsCollection->appendSelections($selectionsCollection, true);

                foreach ($bundleOptions as $bundleOption)
                {
                    if ($bundleOption->getSelections())
                    {
                        $option = [
                            'label' => $bundleOption->getTitle(),
                            'value' => []
                        ];

                        $bundleSelections = $bundleOption->getSelections();

                        foreach ($bundleSelections as $bundleSelection)
                        {
                            $qty = $this->getSelectionQty($product, $bundleSelection->getSelectionId()) * 1;

                            if ($qty)
                            {
                                $option['value'][] = $qty . ' x ' . $this->escapeHtml($bundleSelection->getName())
                                    . ' ' . $this->getSelectionFinalPrice($item, $bundleSelection);
                            }
                        }

                        if ($option['value'])
                        {
                            $options[] = $option;
                        }
                    }
                }
            }
        }

        return $options;
    }
}

