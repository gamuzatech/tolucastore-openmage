<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_CatalogIndex
 */

/**
 * Event observer and indexer running application
 *
 * @package    Mage_CatalogIndex
 */
class Mage_CatalogIndex_Model_Observer extends Mage_Core_Model_Abstract
{
    protected $_parentProductIds = [];
    protected $_productIdsMassupdate = [];

    protected function _construct() {}

    /**
     * Get indexer object
     *
     * @return Mage_CatalogIndex_Model_Indexer
     */
    protected function _getIndexer()
    {
        return Mage::getSingleton('catalogindex/indexer');
    }

    /**
     * Get aggregation object
     *
     * @return Mage_CatalogIndex_Model_Aggregation
     */
    protected function _getAggregator()
    {
        return Mage::getSingleton('catalogindex/aggregation');
    }

    /**
     * Reindex all catalog data
     *
     * @return $this
     */
    public function reindexAll()
    {
        $this->_getIndexer()->plainReindex();
        $this->_getAggregator()->clearCacheData();
        return $this;
    }

    /**
     * Reindex daily related data (prices)
     *
     * @return $this
     */
    public function reindexDaily()
    {
        $this->_getIndexer()->plainReindex(
            null,
            Mage_CatalogIndex_Model_Indexer::REINDEX_TYPE_PRICE,
        );
        $this->clearPriceAggregation();
        return $this;
    }

    /**
     * Process product after save
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processAfterSaveEvent(Varien_Event_Observer $observer)
    {
        $productIds = [];
        /** @var Mage_Catalog_Model_Product $eventProduct */
        $eventProduct = $observer->getEvent()->getProduct();
        $productIds[] = $eventProduct->getId();

        if (!$eventProduct->getIsMassupdate()) {
            $this->_getIndexer()->plainReindex($eventProduct);
        } else {
            $this->_productIdsMassupdate[] = $eventProduct->getId();
        }

        $eventProduct->loadParentProductIds();
        $parentProductIds = $eventProduct->getParentProductIds();
        if ($parentProductIds && !$eventProduct->getIsMassupdate()) {
            $this->_getIndexer()->plainReindex($parentProductIds);
        } elseif ($parentProductIds) {
            $this->_productIdsMassupdate = array_merge($this->_productIdsMassupdate, $parentProductIds);
            $productIds = array_merge($productIds, $parentProductIds);
        }
        $this->_getAggregator()->clearProductData($productIds);
        return $this;
    }

    /**
     * Reindex price data after attribute scope change
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processPriceScopeChange(Varien_Event_Observer $observer)
    {
        $configOption   = $observer->getEvent()->getOption();
        if ($configOption->isValueChanged()) {
            $this->_getIndexer()->plainReindex(
                null,
                Mage_CatalogIndex_Model_Indexer::REINDEX_TYPE_PRICE,
            );
            $this->clearPriceAggregation();
        }
        return $this;
    }

    /**
     * Process catalog index after price rules were applied
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processPriceRuleApplication(Varien_Event_Observer $observer)
    {
        $eventProduct = $observer->getEvent()->getProduct();
        $productCondition = $observer->getEvent()->getProductCondition();
        if ($productCondition) {
            $eventProduct = $productCondition;
        }
        $this->_getIndexer()->plainReindex(
            $eventProduct,
            Mage_CatalogIndex_Model_Indexer::REINDEX_TYPE_PRICE,
        );

        $this->clearPriceAggregation();
        return $this;
    }

    /**
     * Cleanup product index after product delete
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processAfterDeleteEvent(Varien_Event_Observer $observer)
    {
        /** @var Mage_Catalog_Model_Product $eventProduct */
        $eventProduct = $observer->getEvent()->getProduct();
        $eventProduct->setNeedStoreForReindex(true);
        $this->_getIndexer()->cleanup($eventProduct);
        $parentProductIds = $eventProduct->getParentProductIds();

        if ($parentProductIds) {
            $this->_getIndexer()->plainReindex($parentProductIds);
        }
        return $this;
    }

    /**
     * Process index data after attribute information was changed
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processAttributeChangeEvent(Varien_Event_Observer $observer)
    {
        /**
         * @todo add flag to attribute model which will notify what options was changed
         */
        $attribute = $observer->getEvent()->getAttribute();
        $tags = [
            Mage_Eav_Model_Entity_Attribute::CACHE_TAG . ':' . $attribute->getId(),
        ];

        if ($attribute->getOrigData('is_filterable') != $attribute->getIsFilterable()) {
            if ($attribute->getIsFilterable() != 0) {
                $this->_getIndexer()->plainReindex(null, $attribute);
            } else {
                $this->_getAggregator()->clearCacheData($tags);
            }
        } elseif ($attribute->getIsFilterable()) {
            $this->_getAggregator()->clearCacheData($tags);
        }

        return $this;
    }

    /**
     * Create index for new store
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processStoreAdd(Varien_Event_Observer $observer)
    {
        $store = $observer->getEvent()->getStore();
        $this->_getIndexer()->plainReindex(null, null, $store);
        return $this;
    }

    /**
     * Rebuild index after catalog import
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function catalogProductImportAfter(Varien_Event_Observer $observer)
    {
        $this->_getIndexer()->plainReindex();
        $this->_getAggregator()->clearCacheData();
        return $this;
    }

    /**
     * Run planned reindex
     *
     * @return $this
     */
    public function runQueuedIndexing()
    {
        $flag = Mage::getModel('catalogindex/catalog_index_flag')->loadSelf();
        if ($flag->getState() == Mage_CatalogIndex_Model_Catalog_Index_Flag::STATE_QUEUED) {
            $this->_getIndexer()->plainReindex();
            $this->_getAggregator()->clearCacheData();
        }
        return $this;
    }

    /**
     * Clear aggregated layered navigation data
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function cleanCache(Varien_Event_Observer $observer)
    {
        $tagsArray = $observer->getEvent()->getTags();
        $tagName = Mage_CatalogIndex_Model_Aggregation::CACHE_FLAG_NAME;

        if (empty($tagsArray) || in_array($tagName, $tagsArray)) {
            $this->_getAggregator()->clearCacheData();
        }
        return $this;
    }

    /**
     * Process index data after category save
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function catalogCategorySaveAfter(Varien_Event_Observer $observer)
    {
        /** @var Mage_Catalog_Model_Category $category */
        $category = $observer->getEvent()->getCategory();
        if ($category->getInitialSetupFlag()) {
            return $this;
        }
        $tags = [
            Mage_Catalog_Model_Category::CACHE_TAG . ':' . $category->getPath(),
        ];
        $this->_getAggregator()->clearCacheData($tags);
        return $this;
    }

    /**
     * Delete price aggreagation data
     *
     * @return $this
     */
    public function clearPriceAggregation()
    {
        $this->_getAggregator()->clearCacheData([
            Mage_Catalog_Model_Product_Type_Price::CACHE_TAG,
        ]);
        return $this;
    }

    /**
     * Clear layer navigation cache for search results
     *
     * @return $this
     */
    public function clearSearchLayerCache()
    {
        $this->_getAggregator()->clearCacheData([
            Mage_CatalogSearch_Model_Query::CACHE_TAG,
        ]);
        return $this;
    }

    /**
     * Load parent ids for products before deleting
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function registerParentIds(Varien_Event_Observer $observer)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $observer->getEvent()->getProduct();
        $product->loadParentProductIds();
        $productIds = [$product->getId()];
        $productIds = array_merge($productIds, $product->getParentProductIds());
        $this->_getAggregator()->clearProductData($productIds);
        return $this;
    }

    /**
     * Reindex producs after change websites associations
     *
     * @return  Mage_CatalogIndex_Model_Observer
     */
    public function processProductsWebsitesChange(Varien_Event_Observer $observer)
    {
        $productIds = $observer->getEvent()->getProducts();
        $this->_getIndexer()->plainReindex($productIds);
        $this->_getAggregator()->clearProductData($productIds);
        return $this;
    }

    /**
     * Prepare columns for catalog product flat
     *
     * @return $this
     */
    public function catalogProductFlatPrepareColumns(Varien_Event_Observer $observer)
    {
        $columns = $observer->getEvent()->getColumns();

        $this->_getIndexer()->prepareCatalogProductFlatColumns($columns);

        return $this;
    }

    /**
     * Prepare indexes for catalog product flat
     *
     * @return $this
     */
    public function catalogProductFlatPrepareIndexes(Varien_Event_Observer $observer)
    {
        $indexes = $observer->getEvent()->getIndexes();

        $this->_getIndexer()->prepareCatalogProductFlatIndexes($indexes);

        return $this;
    }

    /**
     * Rebuild catalog product flat
     *
     * @return $this
     */
    public function catalogProductFlatRebuild(Varien_Event_Observer $observer)
    {
        $storeId    = $observer->getEvent()->getStoreId();
        $tableName  = $observer->getEvent()->getTable();

        $this->_getIndexer()->updateCatalogProductFlat($storeId, null, $tableName);

        return $this;
    }

    /**
     * Catalog Product Flat update product(s)
     *
     * @return $this
     */
    public function catalogProductFlatUpdateProduct(Varien_Event_Observer $observer)
    {
        $storeId    = $observer->getEvent()->getStoreId();
        $tableName  = $observer->getEvent()->getTable();
        $productIds = $observer->getEvent()->getProductIds();

        $this->_getIndexer()->updateCatalogProductFlat($storeId, $productIds, $tableName);

        return $this;
    }
}
