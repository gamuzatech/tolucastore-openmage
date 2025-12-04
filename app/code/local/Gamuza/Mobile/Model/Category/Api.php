<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Category_Api extends Mage_Catalog_Model_Api_Resource
{
    use Gamuza_Mobile_Trait_Api_Resource;

    protected $_attributeCodes = array (
        'available_sort_by',
        'code',
        'children_count',
        'custom_use_parent_settings',
        'default_sort_by',
        'description',
        'image',
        'include_in_home',
        'include_in_menu',
        'is_active',
        'is_anchor',
        'level',
        'name',
        'path',
        'position',
        'sku',
        'url_key',
        'created_at',
        'updated_at',
    );

    protected $_descCodes  = array ('description');
    protected $_imageCodes = array ('image');
    protected $_floatCodes = array ();
    protected $_intCodes   = array ('children_count', 'level', 'position');
    protected $_boolCodes  = array ('custom_use_parent_settings', 'include_in_home', 'include_in_menu', 'is_active', 'is_anchor');

    protected $_filtersMap = array(
        'category_id' => 'entity_id',
        'parent'      => 'parent_id',
    );

    /**
     * Retrieve list of categories with basic info (id, sku, type, set, name)
     *
     * @param null|object|array $filters
     * @return array
     */
    public function items ($filters = null, $store = null, $media = null, $code = null)
    {
        $storeId = Mage::getStoreConfig (Gamuza_Mobile_Helper_Data::XML_PATH_API_MOBILE_STORE_VIEW, $store);

        $storeCategoryId  = Mage::app ()->getStore ($storeId)->getRootCategoryId ();
        $baseCategoryPath = Mage_Catalog_Model_Category::TREE_ROOT_ID . '/' . $storeCategoryId;

        $defaultSortBy    = Mage::getStoreConfig (Gamuza_Basic_Model_Catalog_Config::XML_PATH_LIST_DEFAULT_SORT_BY,   $storeId);
        $defaultDirection = Mage::getStoreConfig (Gamuza_Basic_Model_Catalog_Config::XML_PATH_LIST_DEFAULT_DIRECTION, $storeId);

        Mage::app ()->getStore ()->setConfig (
            Mage_Catalog_Helper_Category_Flat::XML_PATH_IS_ENABLED_FLAT_CATALOG_CATEGORY, '1'
        );

        $collection = Mage::getModel ('catalog/category')->getCollection ()
            ->setStoreId ($storeId)
        ;

        $collection->getSelect ()->reset (Zend_Db_Select::FROM)
            ->from (array ('e' => Mage::getSingleton ('core/resource')->getTableName ('catalog_category_flat_store_' . $storeId)))
            ->order ('e.name')
        ;

        $collection /* after from */
            ->addStoreFilter ()
            ->addAttributeToSelect ($this->_attributeCodes)
            ->addIsActiveFilter ()
            ->addNameToResult ()
            ->addUrlRewriteToResult ()
        ;

        $collection->getSelect ()->group ('e.entity_id')
            ->where ("e.path LIKE '{$baseCategoryPath}/%'")
            ->order ('e.path')
        ;

        $collection->getSelect ()->reset (Zend_Db_Select::ORDER)
            ->order ('e.position')
            ->order ('e.position ' . $defaultDirection)
        ;

        if (!strcmp ($defaultSortBy, 'position'))
        {
            $defaultSortBy = 'name'; // default
        }

        $collection->getSelect ()->order (sprintf ('e.%s %s', $defaultSortBy, $defaultDirection));

        /** @var $apiHelper Mage_Api_Helper_Data */
        $apiHelper = Mage::helper ('api');

        $filters = $apiHelper->parseFilters ($filters, $this->_filtersMap);

        try
        {
            foreach ($filters as $field => $value)
            {
                // hack for OR condition.
                if (!strcmp (strtoupper ($field), 'OR'))
                {
                    $field = $value;
                    $value = null;
                }

                $collection->addFieldToFilter ($field, $value);
            }
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('filters_invalid', $e->getMessage ());
        }

        $result = array ();

        $mediaUrl = Mage::app ()
            ->getStore (!empty ($media) ? $media : $storeId)
            ->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_MEDIA, false)
        ;

        $storeList = $this->_getStoreList ();

        foreach ($collection as $category)
        {
            $resultCategory = array (
                'entity_id' => intval ($category->getId ()),
                'parent_id' => intval ($category->getParentId ()),
            );

            foreach ($this->_attributeCodes as $code)
            {
                $resultCategory [$code] = $category->getData ($code);
            }

            foreach ($this->_descCodes as $code)
            {
                if (array_key_exists ($code, $resultCategory))
                {
                    $resultCategory [$code] = html_entity_decode (strip_tags ($resultCategory [$code]));
                }
            }

            foreach ($this->_imageCodes as $code)
            {
                $value = $category->getData ($code) ?? 'no_selection';

                if (!empty ($value) && !strcmp ($value, 'no_selection'))
                {
                    $value = Mage::getSingleton ('mobile/core_design_package')
                        ->setStore (!empty ($media) ? $media : $storeId)
                        ->setPackageName ('rwd')
                        ->setTheme ('magento2')
                        ->getSkinUrl ("images/catalog/product/placeholder/{$code}.jpg")
                    ;
                }
                else if (!empty ($value) && strcmp ($value, 'no_selection'))
                {
                    $value = $mediaUrl . 'catalog/category/' . $value; // no_cache
                }

                $resultCategory [$code] = $value;
            }

            foreach ($this->_floatCodes as $code) $resultCategory [$code] = floatval ($resultCategory [$code]);

            foreach ($this->_intCodes as $code) $resultCategory [$code] = intval ($resultCategory [$code]);

            foreach ($this->_boolCodes as $code)
            {
                if (!strcmp ($code, 'free_shipping__'))
                {
                    foreach ($this->_freeshippingAttribute->getSource ()->getAllOptions () as $option)
                    {
                        if (!strcmp ($resultCategory [$code], $option ['value']))
                        {
                            $resultCategory [$code] = !strcmp ($option ['label'], 'Sim') ? true : false;
                        }
                    }
                }
                else
                {
                    $resultCategory [$code] = boolval ($resultCategory [$code]);
                }
            }

            // $resultCategory ['website_ids'] = array_map (function ($n) { return intval ($n); }, $category->getWebsiteIds ());

            $resultCategory ['store_ids'] = array_map (function ($n) { return intval ($n); }, $category->getStoreIds ());

            $resultCategory ['cart_store'] = $storeList [0];

            $resultCategory ['cart_wishlist'] = null;

            Mage::dispatchEvent ('gamuza_mobile_category_list_api_product_after', array ('category' => $category, 'result' => & $resultCategory));

            $result [] = $resultCategory;
        }

        return $result;
    }
}

