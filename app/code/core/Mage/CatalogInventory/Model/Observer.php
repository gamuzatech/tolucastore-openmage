<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_CatalogInventory
 */

/**
 * Catalog inventory module observer
 *
 * @package    Mage_CatalogInventory
 */
class Mage_CatalogInventory_Model_Observer
{
    /**
     * Product qty's checked
     * data is valid if you check quote item qty and use singleton instance
     *
     * @deprecated after 1.4.2.0-rc1
     * @var array
     */
    protected $_checkedProductsQty = [];

    /**
     * Product qty's checked
     * data is valid if you check quote item qty and use singleton instance
     *
     * @var array
     */
    protected $_checkedQuoteItems = [];

    /**
     * Array of items that need to be reindexed
     *
     * @var array
     */
    protected $_itemsForReindex = [];

    /**
     * Array, indexed by product's id to contain stockItems of already loaded products
     * Some kind of singleton for product's stock item
     *
     * @var array
     */
    protected $_stockItemsArray = [];

    /**
     * Add stock information to product
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function addInventoryData($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product instanceof Mage_Catalog_Model_Product) {
            $productId = (int) $product->getId();
            if (!isset($this->_stockItemsArray[$productId])) {
                $this->_stockItemsArray[$productId] = Mage::getModel('cataloginventory/stock_item');
            }
            $productStockItem = $this->_stockItemsArray[$productId];
            $productStockItem->assignProduct($product);
        }
        return $this;
    }

    /**
     * Remove stock information from static variable
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function removeInventoryData($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if (($product instanceof Mage_Catalog_Model_Product)
            && $product->getId()
            && isset($this->_stockItemsArray[$product->getId()])
        ) {
            unset($this->_stockItemsArray[$product->getId()]);
        }
        return $this;
    }

    /**
     * Add information about producs stock status to collection
     * Used in for product collection after load
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function addStockStatusToCollection($observer)
    {
        /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
        $productCollection = $observer->getEvent()->getCollection();
        if ($productCollection->hasFlag('no_stock_data')) {
            return $this;
        }
        if ($productCollection->hasFlag('require_stock_items')) {
            Mage::getModel('cataloginventory/stock')->addItemsToProducts($productCollection);
        } else {
            Mage::getModel('cataloginventory/stock_status')->addStockStatusToProducts($productCollection);
        }
        return $this;
    }

    /**
     * Add Stock items to product collection
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function addInventoryDataToCollection($observer)
    {
        $productCollection = $observer->getEvent()->getProductCollection();
        Mage::getModel('cataloginventory/stock')->addItemsToProducts($productCollection);
        return $this;
    }

    /**
     * Saving product inventory data. Product qty calculated dynamically.
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function saveInventoryData($observer)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $observer->getEvent()->getProduct();

        if (is_null($product->getStockData())) {
            if ($product->getIsChangedWebsites() || $product->dataHasChangedFor('status')) {
                Mage::getSingleton('cataloginventory/stock_status')
                    ->updateStatus($product->getId());
            }
            return $this;
        }

        $item = $product->getStockItem();
        if (!$item) {
            $item = Mage::getModel('cataloginventory/stock_item');
        }
        $this->_prepareItemForSave($item, $product);
        $item->save();
        return $this;
    }

    /**
     * Copy product inventory data (used for product duplicate functionality)
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function copyInventoryData($observer)
    {
        /** @var Mage_Catalog_Model_Product $currentProduct */
        $currentProduct = $observer->getEvent()->getCurrentProduct();
        /** @var Mage_Catalog_Model_Product $newProduct */
        $newProduct = $observer->getEvent()->getNewProduct();

        $newProduct->unsStockItem();
        $stockData = [
            'use_config_min_qty'        => 1,
            'use_config_min_sale_qty'   => 1,
            'use_config_max_sale_qty'   => 1,
            'use_config_backorders'     => 1,
            'use_config_notify_stock_qty' => 1,
        ];
        $currentStockItem = $currentProduct->getStockItem();
        if ($currentStockItem) {
            $stockData += [
                'use_config_enable_qty_inc'  => $currentStockItem->getData('use_config_enable_qty_inc'),
                'enable_qty_increments'             => $currentStockItem->getData('enable_qty_increments'),
                'use_config_qty_increments'         => $currentStockItem->getData('use_config_qty_increments'),
                'qty_increments'                    => $currentStockItem->getData('qty_increments'),
            ];
        }
        $newProduct->setStockData($stockData);

        return $this;
    }

    /**
     * Prepare stock item data for save
     *
     * @param Mage_CatalogInventory_Model_Stock_Item $item
     * @param Mage_Catalog_Model_Product $product
     * @return $this
     */
    protected function _prepareItemForSave($item, $product)
    {
        $item->addData($product->getStockData())
            ->setProduct($product)
            ->setProductId($product->getId())
            ->setStockId($item->getStockId());
        if (!is_null($product->getData('stock_data/min_qty'))
            && is_null($product->getData('stock_data/use_config_min_qty'))
        ) {
            $item->setData('use_config_min_qty', false);
        }
        if (!is_null($product->getData('stock_data/min_sale_qty'))
            && is_null($product->getData('stock_data/use_config_min_sale_qty'))
        ) {
            $item->setData('use_config_min_sale_qty', false);
        }
        if (!is_null($product->getData('stock_data/max_sale_qty'))
            && is_null($product->getData('stock_data/use_config_max_sale_qty'))
        ) {
            $item->setData('use_config_max_sale_qty', false);
        }
        if (!is_null($product->getData('stock_data/backorders'))
            && is_null($product->getData('stock_data/use_config_backorders'))
        ) {
            $item->setData('use_config_backorders', false);
        }
        if (!is_null($product->getData('stock_data/notify_stock_qty'))
            && is_null($product->getData('stock_data/use_config_notify_stock_qty'))
        ) {
            $item->setData('use_config_notify_stock_qty', false);
        }
        $originalQty = $product->getData('stock_data/original_inventory_qty');
        if (is_numeric($originalQty)) {
            $item->setQtyCorrection($item->getQty() - $originalQty);
        }
        if (!is_null($product->getData('stock_data/enable_qty_increments'))
            && is_null($product->getData('stock_data/use_config_enable_qty_inc'))
        ) {
            $item->setData('use_config_enable_qty_inc', false);
        }
        if (!is_null($product->getData('stock_data/qty_increments'))
            && is_null($product->getData('stock_data/use_config_qty_increments'))
        ) {
            $item->setData('use_config_qty_increments', false);
        }
        return $this;
    }

    /**
     * Removes error statuses from quote and item, set by this observer
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @param int $code
     * @return $this
     */
    protected function _removeErrorsFromQuoteAndItem($item, $code)
    {
        if ($item->getHasError()) {
            $params = [
                'origin' => 'cataloginventory',
                'code' => $code,
            ];
            $item->removeErrorInfosByParams($params);
        }

        $quote = $item->getQuote();
        $quoteItems = $quote->getItemsCollection();
        $canRemoveErrorFromQuote = true;

        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        foreach ($quoteItems as $quoteItemId => $quoteItem) {
            if ($quoteItemId == $item->getItemId()) {
                continue;
            }

            $errorInfos = $quoteItem->getErrorInfos();
            foreach ($errorInfos as $errorInfo) {
                if ($errorInfo['code'] == $code) {
                    $canRemoveErrorFromQuote = false;
                    break;
                }
            }

            if (!$canRemoveErrorFromQuote) {
                break;
            }
        }

        if ($quote->getHasError() && $canRemoveErrorFromQuote) {
            $params = [
                'origin' => 'cataloginventory',
                'code' => $code,
            ];
            $quote->removeErrorInfosByParams(null, $params);
        }

        return $this;
    }

    /**
     * Check product inventory data when quote item quantity declaring
     *
     * @param  Varien_Event_Observer $observer
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function checkQuoteItemQty($observer)
    {
        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        $quoteItem = $observer->getEvent()->getItem();
        if (!$quoteItem || !$quoteItem->getProductId() || !$quoteItem->getQuote()
            || $quoteItem->getQuote()->getIsSuperMode()
        ) {
            return $this;
        }

        /**
         * Get Qty
         */
        $qty = $quoteItem->getQty();

        /**
         * Check if product in stock. For composite products check base (parent) item stosk status
         */
        $stockItem = $quoteItem->getProduct()->getStockItem();
        $parentStockItem = false;
        if ($quoteItem->getParentItem()) {
            $parentStockItem = $quoteItem->getParentItem()->getProduct()->getStockItem();
        }
        if ($stockItem) {
            if (!$stockItem->getIsInStock() || ($parentStockItem && !$parentStockItem->getIsInStock())) {
                $quoteItem->addErrorInfo(
                    'cataloginventory',
                    Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                    Mage::helper('cataloginventory')->__('This product is currently out of stock.'),
                );
                $quoteItem->getQuote()->addErrorInfo(
                    'stock',
                    'cataloginventory',
                    Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                    Mage::helper('cataloginventory')->__('Some of the products are currently out of stock.'),
                );
                return $this;
            } else {
                // Delete error from item and its quote, if it was set due to item out of stock
                $this->_removeErrorsFromQuoteAndItem($quoteItem, Mage_CatalogInventory_Helper_Data::ERROR_QTY);
            }
        }

        /**
         * Check item for options
         */
        $options = $quoteItem->getQtyOptions();
        if ($options && $qty > 0) {
            $qty = $quoteItem->getProduct()->getTypeInstance(true)->prepareQuoteItemQty($qty, $quoteItem->getProduct());
            $quoteItem->setData('qty', $qty);

            if ($stockItem) {
                $result = $stockItem->checkQtyIncrements($qty);
                if ($result->getHasError()) {
                    $quoteItem->addErrorInfo(
                        'cataloginventory',
                        Mage_CatalogInventory_Helper_Data::ERROR_QTY_INCREMENTS,
                        $result->getMessage(),
                    );

                    $quoteItem->getQuote()->addErrorInfo(
                        $result->getQuoteMessageIndex(),
                        'cataloginventory',
                        Mage_CatalogInventory_Helper_Data::ERROR_QTY_INCREMENTS,
                        $result->getQuoteMessage(),
                    );
                } else {
                    // Delete error from item and its quote, if it was set due to qty problems
                    $this->_removeErrorsFromQuoteAndItem(
                        $quoteItem,
                        Mage_CatalogInventory_Helper_Data::ERROR_QTY_INCREMENTS,
                    );
                }
            }

            $quoteItemHasErrors = false;
            foreach ($options as $option) {
                /** @var Mage_Sales_Model_Quote_Item_Option $option */
                $optionValue = $option->getValue();
                $optionQty = $qty * $optionValue;
                $increaseOptionQty = ($quoteItem->getQtyToAdd() ? $quoteItem->getQtyToAdd() : $qty) * $optionValue;

                $stockItem = $option->getProduct()->getStockItem();

                if ($quoteItem->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    $stockItem->setParentItem($quoteItem);
                    $stockItem->setProductName($quoteItem->getName());
                }

                /** @var Mage_CatalogInventory_Model_Stock_Item $stockItem */
                if (!$stockItem instanceof Mage_CatalogInventory_Model_Stock_Item) {
                    Mage::throwException(
                        Mage::helper('cataloginventory')->__('The stock item for Product in option is not valid.'),
                    );
                }

                /**
                 * define that stock item is child for composite product
                 */
                $stockItem->setIsChildItem(true);
                /**
                 * don't check qty increments value for option product
                 */
                $stockItem->setSuppressCheckQtyIncrements(true);

                $qtyForCheck = $this->_getQuoteItemQtyForCheck(
                    $option->getProduct()->getId(),
                    $quoteItem->getId(),
                    $increaseOptionQty,
                );

                $result = $stockItem->checkQuoteItemQty($optionQty, $qtyForCheck, $optionValue);

                if (!is_null($result->getItemIsQtyDecimal())) {
                    $option->setIsQtyDecimal($result->getItemIsQtyDecimal());
                }

                if ($result->getHasQtyOptionUpdate()) {
                    $option->setHasQtyOptionUpdate(true);
                    $quoteItem->updateQtyOption($option, $result->getOrigQty());
                    $option->setValue($result->getOrigQty());
                    /**
                     * if option's qty was updates we also need to update quote item qty
                     */
                    $quoteItem->setData('qty', (int) $qty);
                }
                if (!is_null($result->getMessage())) {
                    $option->setMessage($result->getMessage());
                    $quoteItem->setMessage($result->getMessage());
                }
                if (!is_null($result->getItemBackorders())) {
                    $option->setBackorders($result->getItemBackorders());
                }

                if ($result->getHasError()) {
                    $option->setHasError(true);
                    $quoteItemHasErrors = true;

                    $quoteItem->addErrorInfo(
                        'cataloginventory',
                        Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                        $result->getMessage(),
                    );

                    $quoteItem->getQuote()->addErrorInfo(
                        $result->getQuoteMessageIndex(),
                        'cataloginventory',
                        Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                        $result->getQuoteMessage(),
                    );
                } elseif (!$quoteItemHasErrors) {
                    // Delete error from item and its quote, if it was set due to qty lack
                    $this->_removeErrorsFromQuoteAndItem($quoteItem, Mage_CatalogInventory_Helper_Data::ERROR_QTY);
                }

                $stockItem->unsIsChildItem();
            }
        } else {
            /** @var Mage_CatalogInventory_Model_Stock_Item $stockItem */
            if (!$stockItem instanceof Mage_CatalogInventory_Model_Stock_Item) {
                Mage::throwException(Mage::helper('cataloginventory')->__('The stock item for Product is not valid.'));
            }

            /**
             * When we work with subitem (as subproduct of bundle or configurable product)
             */
            if ($quoteItem->getParentItem()) {
                $rowQty = $quoteItem->getParentItem()->getQty() * $qty;
                /**
                 * we are using 0 because original qty was processed
                 */
                $qtyForCheck = $this->_getQuoteItemQtyForCheck(
                    $quoteItem->getProduct()->getId(),
                    $quoteItem->getId(),
                    0,
                );
            } else {
                $increaseQty = $quoteItem->getQtyToAdd() ? $quoteItem->getQtyToAdd() : $qty;
                $rowQty = $qty;
                $qtyForCheck = $this->_getQuoteItemQtyForCheck(
                    $quoteItem->getProduct()->getId(),
                    $quoteItem->getId(),
                    $increaseQty,
                );
            }

            $productTypeCustomOption = $quoteItem->getProduct()->getCustomOption('product_type');
            if (!is_null($productTypeCustomOption)) {
                // Check if product related to current item is a part of grouped product
                if ($productTypeCustomOption->getValue() == Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE) {
                    $stockItem->setProductName($quoteItem->getProduct()->getName());
                    $stockItem->setIsChildItem(true);
                }
            }

            $result = $stockItem->checkQuoteItemQty($rowQty, $qtyForCheck, $qty);

            if ($stockItem->hasIsChildItem()) {
                $stockItem->unsIsChildItem();
            }

            if (!is_null($result->getItemIsQtyDecimal())) {
                $quoteItem->setIsQtyDecimal($result->getItemIsQtyDecimal());
                if ($quoteItem->getParentItem()) {
                    $quoteItem->getParentItem()->setIsQtyDecimal($result->getItemIsQtyDecimal());
                }
            }

            /**
             * Just base (parent) item qty can be changed
             * qty of child products are declared just during add process
             * exception for updating also managed by product type
             */
            if ($result->getHasQtyOptionUpdate()
                && (
                    !$quoteItem->getParentItem()
                    || $quoteItem->getParentItem()->getProduct()->getTypeInstance(true)
                        ->getForceChildItemQtyChanges($quoteItem->getParentItem()->getProduct())
                )
            ) {
                $quoteItem->setData('qty', $result->getOrigQty());
            }

            if (!is_null($result->getItemUseOldQty())) {
                $quoteItem->setUseOldQty($result->getItemUseOldQty());
            }
            if (!is_null($result->getMessage())) {
                $quoteItem->setMessage($result->getMessage());
            }

            if (!is_null($result->getItemBackorders())) {
                $quoteItem->setBackorders($result->getItemBackorders());
            }

            if ($result->getHasError()) {
                $quoteItem->addErrorInfo(
                    'cataloginventory',
                    Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                    $result->getMessage(),
                );

                $quoteItem->getQuote()->addErrorInfo(
                    $result->getQuoteMessageIndex(),
                    'cataloginventory',
                    Mage_CatalogInventory_Helper_Data::ERROR_QTY,
                    $result->getQuoteMessage(),
                );
            } else {
                // Delete error from item and its quote, if it was set due to qty lack
                $this->_removeErrorsFromQuoteAndItem($quoteItem, Mage_CatalogInventory_Helper_Data::ERROR_QTY);
            }
        }

        return $this;
    }

    /**
     * Get product qty includes information from all quote items
     * Need be used only in sungleton mode
     *
     * @param int $productId
     * @param float $itemQty
     * @return float|mixed
     * @deprecated after 1.4.2.0-rc1
     */
    protected function _getProductQtyForCheck($productId, $itemQty)
    {
        $qty = $itemQty;
        if (isset($this->_checkedProductsQty[$productId])) {
            $qty += $this->_checkedProductsQty[$productId];
        }
        $this->_checkedProductsQty[$productId] = $qty;
        return $qty;
    }

    /**
     * Get product qty includes information from all quote items
     * Need be used only in sungleton mode
     *
     * @param int   $productId
     * @param int   $quoteItemId
     * @param float $itemQty
     * @return float
     */
    protected function _getQuoteItemQtyForCheck($productId, $quoteItemId, $itemQty)
    {
        $qty = $itemQty;
        if (isset($this->_checkedQuoteItems[$productId]['qty']) &&
            !in_array($quoteItemId, $this->_checkedQuoteItems[$productId]['items'])
        ) {
            $qty += $this->_checkedQuoteItems[$productId]['qty'];
        }

        $this->_checkedQuoteItems[$productId]['qty'] = $qty;
        $this->_checkedQuoteItems[$productId]['items'][] = $quoteItemId;

        return $qty;
    }

    /**
     * Subtract qtys of quote item products after multishipping checkout
     *
     * @return $this
     */
    public function checkoutAllSubmitAfter(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        if (!$quote->getInventoryProcessed()) {
            $this->subtractQuoteInventory($observer);
            $this->reindexQuoteInventory($observer);
        }
        return $this;
    }

    /**
     * Subtract quote items qtys from stock items related with quote items products.
     *
     * Used before order placing to make order save/place transaction smaller
     * Also called after every successful order placement to ensure subtraction of inventory
     *
     * @return Mage_CatalogInventory_Model_Observer|void
     */
    public function subtractQuoteInventory(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        // Maybe we've already processed this quote in some event during order placement
        // e.g. call in event 'sales_model_service_quote_submit_before' and later in 'checkout_submit_all_after'
        if ($quote->getInventoryProcessed()) {
            return;
        }
        $items = $this->_getProductsQty($quote->getAllItems());

        /**
         * Remember items
         */
        $this->_itemsForReindex = Mage::getSingleton('cataloginventory/stock')->registerProductsSale($items);

        $quote->setInventoryProcessed(true);
        return $this;
    }

    /**
     * Revert quote items inventory data (cover not success order place case)
     * @param Varien_Event_Observer $observer
     */
    public function revertQuoteInventory($observer)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        $items = $this->_getProductsQty($quote->getAllItems());
        Mage::getSingleton('cataloginventory/stock')->revertProductsSale($items);

        // Clear flag, so if order placement retried again with success - it will be processed
        $quote->setInventoryProcessed(false);
    }

    /**
     * Adds stock item qty to $items (creates new entry or increments existing one)
     * $items is array with following structure:
     * array(
     *  $productId  => array(
     *      'qty'   => $qty,
     *      'item'  => $stockItems|null
     *  )
     * )
     *
     * @param Mage_Sales_Model_Quote_Item $quoteItem
     * @param array $items
     */
    protected function _addItemToQtyArray($quoteItem, &$items)
    {
        $productId = $quoteItem->getProductId();
        if (!$productId) {
            return;
        }
        if (isset($items[$productId])) {
            $items[$productId]['qty'] += $quoteItem->getTotalQty();
        } else {
            $stockItem = null;
            if ($quoteItem->getProduct()) {
                $stockItem = $quoteItem->getProduct()->getStockItem();
            }
            $items[$productId] = [
                'item' => $stockItem,
                'qty'  => $quoteItem->getTotalQty(),
            ];
        }
    }

    /**
     * Prepare array with information about used product qty and product stock item
     * result is:
     * array(
     *  $productId  => array(
     *      'qty'   => $qty,
     *      'item'  => $stockItems|null
     *  )
     * )
     * @param array $relatedItems
     * @return array
     */
    protected function _getProductsQty($relatedItems)
    {
        $items = [];
        foreach ($relatedItems as $item) {
            $productId  = $item->getProductId();
            if (!$productId) {
                continue;
            }
            $children = $item->getChildrenItems();
            if ($children) {
                foreach ($children as $childItem) {
                    $this->_addItemToQtyArray($childItem, $items);
                }
            } else {
                $this->_addItemToQtyArray($item, $items);
            }
        }
        return $items;
    }

    /**
     * Refresh stock index for specific stock items after successful order placement
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function reindexQuoteInventory($observer)
    {
        // Reindex quote ids
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        $productIds = [];
        foreach ($quote->getAllItems() as $item) {
            $productId = $item->getProductId();
            $productIds[$productId] = $productId;
            $children   = $item->getChildrenItems();
            if ($children) {
                foreach ($children as $childItem) {
                    $childItemProductId = $childItem->getProductId();
                    $productIds[$childItemProductId] = $childItemProductId;
                }
            }
        }

        /**
         * ref: https://github.com/OpenMage/magento-lts/issues/152
         *
         * get product stock setup.
         * We only want to re-index products that actually have stock management setup.
         * This limits the number of stock re-indexing that takes place,
         * especially in stores where stock is not managed
         **/
        $productIds = array_map('\intval', $productIds);
        $stockCollection = Mage::getModel('cataloginventory/stock_item')->getCollection()
            ->addFieldToFilter('product_id', ['in' => $productIds])
            ->addFieldToFilter('manage_stock', ['eq' => 1]);
        $stockCollection->getSelect()->reset(Zend_Db_Select::COLUMNS)->columns(['product_id']);
        $productIds = $stockCollection->getColumnValues('product_id');

        if (count($productIds)) {
            Mage::getResourceSingleton('cataloginventory/indexer_stock')->reindexProducts($productIds);
        }

        // Reindex previously remembered items
        $productIds = [];
        foreach ($this->_itemsForReindex as $item) {
            // phpcs:ignore Ecg.Performance.Loop.ModelLSD
            $item->save();
            $productIds[] = $item->getProductId();
        }
        Mage::getResourceSingleton('catalog/product_indexer_price')->reindexProductIds($productIds);

        $this->_itemsForReindex = []; // Clear list of remembered items - we don't need it anymore

        return $this;
    }

    /**
     * Return creditmemo items qty to stock
     *
     * @param Varien_Event_Observer $observer
     */
    public function refundOrderInventory($observer)
    {
        /** @var Mage_Sales_Model_Order_Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $items = [];
        foreach ($creditmemo->getAllItems() as $item) {
            /** @var Mage_Sales_Model_Order_Creditmemo_Item $item */
            $return = false;
            if ($item->hasBackToStock()) {
                if ($item->getBackToStock() && $item->getQty()) {
                    $return = true;
                }
            } elseif (Mage::helper('cataloginventory')->isAutoReturnEnabled()) {
                $return = true;
            }
            if ($return) {
                $parentOrderId = $item->getOrderItem()->getParentItemId();
                /** @var Mage_Sales_Model_Order_Creditmemo_Item $parentItem */
                $parentItem = $parentOrderId ? $creditmemo->getItemByOrderId($parentOrderId) : false;
                $qty = $parentItem ? ($parentItem->getQty() * $item->getQty()) : $item->getQty();
                if (isset($items[$item->getProductId()])) {
                    $items[$item->getProductId()]['qty'] += $qty;
                } else {
                    $items[$item->getProductId()] = [
                        'qty'  => $qty,
                        'item' => null,
                    ];
                }
            }
        }
        Mage::getSingleton('cataloginventory/stock')->revertProductsSale($items);
    }

    /**
     * Cancel order item
     *
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function cancelOrderItem($observer)
    {
        /** @var Mage_Sales_Model_Order_Item $item */
        $item = $observer->getEvent()->getItem();

        $children = $item->getChildrenItems();
        $qty = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();

        $productId = $item->getProductId();
        if ($item->getId() && $productId && empty($children) && $qty) {
            Mage::getSingleton('cataloginventory/stock')->backItemQty($productId, $qty);
        }

        return $this;
    }

    /**
     * Update items stock status and low stock date.
     *
     * @param Varien_Event_Observer $observer
     * @return  $this
     */
    public function updateItemsStockUponConfigChange($observer)
    {
        Mage::getResourceSingleton('cataloginventory/stock')->updateSetOutOfStock();
        Mage::getResourceSingleton('cataloginventory/stock')->updateSetInStock();
        Mage::getResourceSingleton('cataloginventory/stock')->updateLowStockDate();
        return $this;
    }

    /**
     * Update Only product status observer
     *
     * @return $this
     */
    public function productStatusUpdate(Varien_Event_Observer $observer)
    {
        $productId = $observer->getEvent()->getProductId();
        Mage::getSingleton('cataloginventory/stock_status')
            ->updateStatus($productId);
        return $this;
    }

    /**
     * Catalog Product website update
     *
     * @return $this
     */
    public function catalogProductWebsiteUpdate(Varien_Event_Observer $observer)
    {
        $websiteIds = $observer->getEvent()->getWebsiteIds();
        $productIds = $observer->getEvent()->getProductIds();

        foreach ($websiteIds as $websiteId) {
            foreach ($productIds as $productId) {
                Mage::getSingleton('cataloginventory/stock_status')
                    ->updateStatus($productId, null, $websiteId);
            }
        }

        return $this;
    }

    /**
     * Add stock status to prepare index select
     *
     * @return $this
     */
    public function addStockStatusToPrepareIndexSelect(Varien_Event_Observer $observer)
    {
        $website    = $observer->getEvent()->getWebsite();
        $select     = $observer->getEvent()->getSelect();

        Mage::getSingleton('cataloginventory/stock_status')
            ->addStockStatusToSelect($select, $website);

        return $this;
    }

    /**
     * Add stock status limitation to catalog product price index select object
     *
     * @return $this
     */
    public function prepareCatalogProductIndexSelect(Varien_Event_Observer $observer)
    {
        $select     = $observer->getEvent()->getSelect();
        $entity     = $observer->getEvent()->getEntityField();
        $website    = $observer->getEvent()->getWebsiteField();

        Mage::getSingleton('cataloginventory/stock_status')
            ->prepareCatalogProductIndexSelect($select, $entity, $website);

        return $this;
    }

    /**
     * Add stock status filter to select
     *
     * @return $this
     */
    public function addStockStatusFilterToSelect(Varien_Event_Observer $observer)
    {
        $select         = $observer->getEvent()->getSelect();
        $entityField    = $observer->getEvent()->getEntityField();
        $websiteField   = $observer->getEvent()->getWebsiteField();

        if ($entityField === null || $websiteField === null) {
            return $this;
        }

        if (!($entityField instanceof Zend_Db_Expr)) {
            $entityField = new Zend_Db_Expr($entityField);
        }
        if (!($websiteField instanceof Zend_Db_Expr)) {
            $websiteField = new Zend_Db_Expr($websiteField);
        }

        Mage::getResourceSingleton('cataloginventory/stock_status')
            ->prepareCatalogProductIndexSelect($select, $entityField, $websiteField);

        return $this;
    }

    /**
     * Lock DB rows for order products
     *
     * We need do it for resolving problems with inventory on placing
     * some orders in one time
     * @deprecated after 1.4
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function lockOrderInventoryData($observer)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getEvent()->getOrder();
        $productIds = [];

        /**
         * Do lock only for new order
         */
        if ($order->getId()) {
            return $this;
        }

        if ($order) {
            foreach ($order->getAllItems() as $item) {
                $productIds[] = $item->getProductId();
            }
        }

        if (!empty($productIds)) {
            Mage::getSingleton('cataloginventory/stock')->lockProductItems($productIds);
        }

        return $this;
    }

    /**
     * Register saving order item
     *
     * @deprecated after 1.4
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function createOrderItem($observer)
    {
        /** @var Mage_Sales_Model_Order_Item $item */
        $item = $observer->getEvent()->getItem();
        /**
         * Before creating order item need subtract ordered qty from product stock
         */

        $children = $item->getChildrenItems();

        if (!$item->getId() && empty($children)) {
            Mage::getSingleton('cataloginventory/stock')->registerItemSale($item);
        }

        return $this;
    }

    /**
     * Back refunded item qty to stock
     *
     * @deprecated after 1.4
     * @param   Varien_Event_Observer $observer
     * @return  $this
     */
    public function refundOrderItem($observer)
    {
        /** @var Mage_Sales_Model_Order_Creditmemo_Item $item */
        $item = $observer->getEvent()->getCreditmemoItem();
        if ($item->getId() && $item->getBackToStock() && ($productId = $item->getProductId())
            && ($qty = $item->getQty())
        ) {
            Mage::getSingleton('cataloginventory/stock')->backItemQty($productId, $qty);
        }
        return $this;
    }

    /**
     * Reindex all events of product-massAction type
     *
     * @param Varien_Event_Observer $observer
     * @throws Exception
     */
    public function reindexProductsMassAction($observer)
    {
        Mage::getSingleton('index/indexer')->indexEvents(
            Mage_Catalog_Model_Product::ENTITY,
            Mage_Index_Model_Event::TYPE_MASS_ACTION,
        );
    }

    /**
     * Detects whether product status should be shown
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function displayProductStatusInfo($observer)
    {
        $info = $observer->getEvent()->getStatus();
        $info->setDisplayStatus(Mage::helper('cataloginventory')->isDisplayProductStockStatus());
        return $this;
    }
}
