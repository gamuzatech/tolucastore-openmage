<?php
/*
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog Search API
 */
class Gamuza_Mobile_Model_CatalogSearch_Api extends Mage_Api_Model_Resource_Abstract
{
    const FILTER_REQUEST_VAR_PRICE = 'price';

    protected $_attributeCodes = array (
        'brand',
        'brand_value',
        'code',
        'color',
        'color_value',
        'description',
        'free_shipping',
        'gift_message_available',
        'has_options',
        'image',
        'image_label',
        'name',
        'news_from_date',
        'news_to_date',
        'offer_type',
        'price',
        'price_type',
        'price_view',
        'required_options',
        'short_description',
        'size',
        'size_value',
        'sku',
        'sku_position',
        'small_image',
        'small_image_label',
        'special_from_date',
        'special_price',
        'special_to_date',
        'status',
        'thumbnail',
        'thumbnail_label',
        'type_id',
        'url_key',
        'url_path',
        'visibility',
        'volume_altura',
        'volume_comprimento',
        'volume_largura',
        'weight',
        'created_at',
        'updated_at',
    );

    protected $_productAttributes = array (
        'entity_id', 'type_id', 'name', 'short_description',
        'price', 'special_price', 'special_from_date', 'special_to_date',
        'image', 'small_image', 'thumbnail', 'news_from_date', 'news_to_date',
        'status', 'required_options', 'final_price', 'minimal_price',
        'min_price', 'max_price', 'cat_index_position',
        'do_not_use_category_id', 'is_salable', 'free_shipping'
    );

    protected $_intAttributes = array ('entity_id', 'status');

    protected $_floatAttributes = array ('weight', 'price', /* 'special_price' */);

    protected $_boolAttributes = array (
        'required_options', 'is_in_stock', 'is_salable', 'free_shipping'
    );

    protected $_mediaAttributes = array ('image', 'small_image', 'thumbnail');

    protected $_descCodes = array ('description', 'short_description');

    protected $_storeId = null;

    public function __construct ()
    {
        $this->_storeId = Mage::getStoreConfig ('api/mobile/store_view');
    }

    public function result ($text, $params = array (), $code = null)
    {
        Mage::app ()->getRequest ()->setParam (Mage_CatalogSearch_Helper_Data::QUERY_VAR_NAME, $text);
        Mage::app ()->getRequest ()->setQuery ($params);

        Mage::app ()->setCurrentStore ($this->_storeId);

        Mage::app ()->getStore ()->setConfig (
            Mage_Catalog_Helper_Product_Flat::XML_PATH_USE_PRODUCT_FLAT, '1'
        );

        /* @var $query Mage_CatalogSearch_Model_Query */
        $query = Mage::helper ('catalogsearch')->getQuery ();

        $query->setStoreId (Mage::app ()->getStore ()->getId ());

        $queryText = $query->getQueryText ();
        if (empty ($queryText))
        {
            $this->_fault ('query_not_specified');
        }

        if (Mage::helper ('catalogsearch')->isMinQueryLength ())
        {
            $query->setId (0)
                ->setIsActive (1)
                ->setIsProcessed (1)
            ;

            $this->_fault ('query_invalid_length');
        }
        else
        {
            $query->setPopularity ($query->getId () ? intval ($query->getPopularity ()) + 1 : 1);

            if ($query->getRedirect ())
            {
                $query->save ();

                // $this->getResponse ()->setRedirect ($query->getRedirect ());

                return null;
            }
            else
            {
                $query->prepare ();
            }
        }

        Mage::helper ('catalogsearch')->checkNotes ();

        $block = Mage::app ()->getLayout ()->createBlock ('catalogSearch/layer');

        if (!$block->canShowBlock ())
        {
            $this->_fault ('query_no_result');
        }

        $result = array (
            'states'   => array (),
            'filters'  => array (),
            'toolbar'  => array (),
            'pager'    => array (),
            'products' => array ()
        );

        foreach ($block->getLayer ()->getState ()->getFilters () as $filter)
        {
            $requestVar = $filter->getFilter ()->getRequestVar ();
            $value      = $filter->getValue ();

            if (!strcmp ($requestVar, self::FILTER_REQUEST_VAR_PRICE) && is_array ($value))
            {
                $value = implode ("", array_map (function ($_value) { return empty ($_value) ? '-' : $_value; }, $value));
            }

            $result ['states'][] = array(
                'name'  => $block->__($filter->getName ()),
                'label' => strip_tags ($filter->getLabel ()),
                'var'   => $requestVar,
                'value' => $value,
                // 'count' => $filter->getCount ()
            );
        }

        if ($block->canShowOptions ())
        {
            foreach ($block->getFilters () as $filter)
            {
                if ($filter->getItemsCount ())
                {
                    $filterResult = array(
                        'name' => $block->__($filter->getName ()),
                        'display_count' => $filter->shouldDisplayProductCount ()
                    );

                    foreach ($filter->getItems () as $item)
                    {
                        $filterResult ['items'][] = array(
                            'label' => strip_tags ($item->getLabel ()),
                            'count' => intval ($item->getCount ()),
                            'var'   => $item->getFilter ()->getRequestVar (),
                            'value' => $item->getValue ()
                        );
                    }

                    $result ['filters'][] = $filterResult;
                }
            }
        }

        $block = Mage::app ()->getLayout ()->createBlock ('catalog/product_list');

        $collection = $block->getLoadedProductCollection ();

        $collection->getSelect ()
            // ->reset (Zend_Db_Select::COLUMNS)
            /*
            ->join (
                array ('relation' => Mage::getSingleton ('core/resource')->getTableName ('catalog_product_relation')),
                'e.entity_id = relation.parent_id',
                array ('GROUP_CONCAT(child_id) as child_ids')
            )->join (
                array ('stock' => Mage::getSingleton ('core/resource')->getTableName ('cataloginventory_stock_item')),
                'relation.child_id = stock.product_id',
                array ('GROUP_CONCAT(stock.qty) as child_qtys')
            )
            */
        ;

        $collection->addAttributeToSelect ($this->_productAttributes)
            ->addAttributeToSelect ($this->_attributeCodes)
            ->setFlag ('require_stock_items', true)
            // ->joinTable ('cataloginventory/stock_item', 'product_id = entity_id', array ('is_in_stock'))
        ;

        $collection->getSelect ()
            ->columns(
                array(
                    'short_description' => 'e.short_description',
                    'thumbnail'         => 'e.thumbnail'
                )
            )
        ; // product

        $toolbar = Mage::app ()->getLayout ()->createBlock ('catalog/product_list_toolbar')
            ->disableParamsMemorizing ()
            ->setCollection ($collection)
        ;

        if (!$collection->count ())
        {
            $this->_fault ('query_no_product');
        }

        $result ['products']['count'] = $collection->count ();

        $mediaUrl = Mage::app ()->getStore (Mage_Core_Model_App::ADMIN_STORE_ID)->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_MEDIA, false);

        $collection->addOptionsToResult ();
/*
        $productAttributes = Mage::getResourceModel ('catalog/product_attribute_collection')
            ->setEntityTypeFilter (Mage_Catalog_Model_Product::ENTITY)
            ->addFieldToFilter ('main_table.attribute_code',            array ('in' => $this->_attributeCodes))
            ->addFieldToFilter ('additional_table.is_visible_on_front', array ('eq' => '1'))
        ;

        $productAttributes->getSelect ()
            ->reset (Zend_Db_Select::COLUMNS)
            ->columns (array(
                'attribute_id', 'attribute_code', 'frontend_label',
                'is_visible_on_front' => 'additional_table.is_visible_on_front',
            ))
        ;
*/
        foreach ($collection as $product)
        {
            $productData = $product->getData ();

            foreach ($this->_intAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $productData))
                {
                    $productData [$attribute] = intval ($productData [$attribute]);
                }
            }

            foreach ($this->_floatAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $productData))
                {
                    $productData [$attribute] = floatval ($productData [$attribute]);
                }
            }

            foreach ($this->_boolAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $productData))
                {
                    $productData [$attribute] = boolval ($productData [$attribute]);
                }
            }

            foreach ($this->_mediaAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $productData))
                {
                    $productData [$attribute] = $mediaUrl . 'catalog/product' . $productData [$attribute];
                }
            }

            foreach ($this->_descCodes as $code)
            {
                if (array_key_exists ($code, $productData))
                {
                    $productData [$code] = html_entity_decode (strip_tags ($productData [$code]));
                }
            }

            $productData ['additional_attributes'] = array ();

            foreach ($productAttributes as $attribute)
            {
                $code = $attribute->getAttributeCode ();

                if ($attribute->getIsVisibleOnFront () && in_array ($code, $this->_attributeCodes))
                {
                    $value = $attribute->getFrontend ()->getValue ($product);

                    $productData ['additional_attributes'][] = array(
                        'attribute_code' => $attribute->getAttributeCode (),
                        'store_label'    => $attribute->getStoreLabel (),
                        'value'          => $value,
                        'raw'            => $product->getData ($code)
                    );
                }
            }

            $productData ['super_attributes'] = array ();

            if (!strcmp ($product->getTypeId (), Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE))
            {
                $productData ['super_attributes'] = $product->getTypeInstance (true)->getConfigurableAttributesAsArray ($product);
            }

            $productData ['website_ids'] = array_map (function ($n) { return intval ($n); }, $product->getWebsiteIds ());

            $productData ['store_ids'] = array_map (function ($n) { return intval ($n); }, $product->getStoreIds ());

            $stockItem = $product->getStockItem ();
            if ($stockItem instanceof Mage_CatalogInventory_Model_Stock_Item)
            {
                $productData ['stock_item'] = array(
                    'qty'                   => intval ($stockItem->getQty ()),
                    'min_sale_qty'          => intval ($stockItem->getMinSaleQty ()),
                    'max_sale_qty'          => intval ($stockItem->getMaxSaleQty ()),
                    'backorders'            => intval ($stockItem->getBackOrders ()),
                    'is_in_stock'           => boolval ($stockItem->getIsInStock ()),
                    'enable_qty_increments' => boolval ($stockItem->getEnableQtyIncrements ()),
                    'qty_increments'        => intval ($stockItem->getQtyIncrements ()),
                );
            }

            $productData ['cart_store'] = null;

            $productData ['cart_wishlist'] = null;
            /*
            $storeIds = array_filter ($product->getStoreIds (), function ($value) {
                return !in_array ($value, array ($this->_storeId, Mage_Core_Model_App::DISTRO_STORE_ID));
            });
            */
            $storeIds = array_filter ($product->getStoreIds (), function ($value) {
                $_storeIds  = Mage::app ()->getStore ($this->_storeId)->getWebsite ()->getStoreIds ();

                return !in_array ($value, $_storeIds);
            });

            $storeCollection = Mage::getModel ('core/store')->getCollection ()
                // ->addFieldToFilter ('is_visible', array ('eq' => '1'))
                ->addIdFilter ($storeIds)
            ;

            if ($storeCollection->count ())
            {
                $store = $storeCollection->getFirstItem ();

                $productData ['cart_store'] = array (
                    // comment box
                    'is_delivery'    => boolval ($store->getIsDelivery ()),
                    'is_sexshop'     => boolval ($store->getIsSexshop ()),
                    // store info
                    'entity_id'  => intval ($store->getId ()),
                    'code'       => $store->getCode (),
                    'name'       => $store->getName (),
                    'sort_order' => intval ($store->getSortOrder ()),
                    'url'        => $store->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_LINK, false)
                );
            }

            $productData ['has_options']          = $product->getTypeInstance (true)->hasOptions ($product);
            $productData ['has_required_options'] = $product->getTypeInstance (true)->hasRequiredOptions ($product);

            if (!empty ($productData ['has_options']) || !empty ($productData ['has_required_options']))
            {
                foreach ($product->getOptions () as $option)
                {
                    $resultOption = array(
                        'option_id'     => intval ($option->getId ()),
                        'product_id'    => intval ($option->getProductId ()),
                        'type'          => $option->getType (),
                        'is_require'    => boolval ($option->getIsRequire ()),
                        'sort_order'    => intval ($option->getSortOrder ()),
                        'max_length'    => intval ($option->getMaxLength ()),
                        'default_title' => $option->getDefaultTitle (),
                        'title'         => $option->getTitle (),
                    );

                    foreach ($option->getValues () as $value)
                    {
                        $resultOption ['values'][] = array(
                            'option_type_id'     => intval ($value->getOptionTypeId ()),
                            'option_id'          => intval ($value->getOptionId ()),
                            'sort_order'         => intval ($value->getSortOrder ()),
                            'default_price'      => floatval ($value->getDefaultPrice ()),
                            'default_price_type' => $value->getDefaultPriceType (),
                            'store_price'        => floatval ($value->getStorePrice ()),
                            'store_price_type'   => $value->getStorePriceType (),
                            'price'              => floatval ($value->getPrice ()),
                            'price_type'         => $value->getPriceType (),
                            'default_title'      => $value->getDefaultTitle (),
                            'store_title'        => $value->getStoreTitle (),
                            'title'              => $value->getTitle (),
                        );
                    }

                    $productData ['options'][] = $resultOption;
                }
            }

            $productData ['price_view'] = intval ($product->getPriceView ());
            $productData ['price_type'] = intval ($product->getPriceType ());

            $result ['products']['items'][] = $productData;
        }

        if ($toolbar->getCollection ()->getSize ())
        {
            if ($toolbar->isExpanded ())
            {
                $result ['toolbar']['current_direction'] = $toolbar->getCurrentDirection ();

                foreach ($toolbar->getAvailableOrders () as $_key => $_order)
                {
                    if ($toolbar->isOrderCurrent ($_key))
                    {
                        $result ['toolbar']['current_order'] = $_key;
                    }

                    $result ['toolbar']['orders'][] = array ('key' => $_key, 'value' => $toolbar->__($_order));
                }
            }

            $result ['toolbar']['amount_label'] = $toolbar->getLastPageNum () > 1
                ? $toolbar->__('%s-%s of %s', $toolbar->getFirstNum (), $toolbar->getLastNum (), $toolbar->getTotalNum ())
                : $toolbar->__('%s Item(s)',  $toolbar->getTotalNum ())
            ;

            foreach ($toolbar->getAvailableLimit () as $_key => $_limit)
            {
                if ($toolbar->isLimitCurrent ($_key))
                {
                    $result ['toolbar']['current_limit'] = strval ($_key);
                }

                $result ['toolbar']['limits'][] = array ('key' => strval ($_key), 'value' => $_limit);
            }
        }

        $block = Mage::app ()->getLayout ()->createBlock ('mobile/page_html_pager')
            ->setUseContainer (false)
            ->setShowPerPage (false)
            ->setShowAmounts (false)
            ->setLimitVarName ($toolbar->getLimitVarName())
            ->setPageVarName ($toolbar->getPageVarName())
            ->setAvailableLimit ($toolbar->getAvailableLimit ())
            ->setLimit ($toolbar->getLimit())
            ->setFrameLength (Mage::getStoreConfig ('design/pagination/pagination_frame'))
            ->setJump (Mage::getStoreConfig ('design/pagination/pagination_frame_skip'))
            ->setCollection ($collection)
        ;

        if ($block->getCollection ()->getSize ())
        {
            if ($block->getShowAmounts () || $block->getShowPerPage ())
            {
                if ($block->getShowAmounts ())
                {
                    $result ['pager']['amount_label'] = $block->getLastPageNum () > 1
                        ? $block->__('%s-%s of %s', $block->getFirstNum (), $block->getLastNum (), $block->getTotalNum ())
                        : $block->__('%s Item(s)',  $block->getTotalNum ())
                    ;
                }

                if ($block->getShowPerPage ())
                {
                    foreach ($block->getAvailableLimit () as $_key => $_limit)
                    {
                        if ($block->isLimitCurrent ($_key))
                        {
                            $result ['pager']['current_limit'] = $_key;
                        }

                        $result ['pager']['limits'] = array (strval ($_key), $_limit);
                    }
                }
            }

            if ($block->getLastPageNum () > 1)
            {
                $result ['pager']['last_page_num'] = $block->getLastPageNum ();
                $result ['pager']['current_page']  = $block->getCurrentPage ();

                $result ['pager']['limit']           = $block->getLimit ();
                $result ['pager']['available_limit'] = $block->getAvailableLimit ();

                $result ['pager']['first_num'] = $block->getFirstNum ();
                $result ['pager']['last_num']  = $block->getLastNum ();
                $result ['pager']['total_num'] = $block->getTotalNum ();

                foreach ($block->getFramePages () as $_page)
                {
                    $result ['pager']['frames'][] = $_page;
                }

                $result ['pager']['pages'] = array(
                    'is_first_page'            => $block->isFirstPage (),
                    'is_last_page'             => $block->isLastPage (),
                    'anchor_text_for_previous' => $block->getAnchorTextForPrevious (),
                    'anchor_text_for_next'     => $block->getAnchorTextForNext (),
                    'can_show_first'           => $block->canShowFirst (),
                    'can_show_last'            => $block->canShowLast (),
                    'can_show_previous_jump'   => $block->canShowPreviousJump (),
                    'can_show_next_jump'       => $block->canShowNextJump (),
                    /* url */
                    'first_page_url'           => $block->getFirstPageUrl (),
                    'last_page_url'            => $block->getLastPageUrl (),
                    'previous_page_url'        => $block->getPreviousPageUrl (),
                    'next_page_url'            => $block->getNextPageUrl (),
                    'previous_jump_url'        => $block->getPreviousJumpUrl (),
                    'next_jump_url'            => $block->getNextJumpUrl (),
                );
            }
            else
            {
                $result ['pager']['last_page_num'] = 0;
            }
        }

        if (!Mage::helper ('catalogsearch')->isMinQueryLength ())
        {
            $query->save ();
        }

        return $result;
    }
}

