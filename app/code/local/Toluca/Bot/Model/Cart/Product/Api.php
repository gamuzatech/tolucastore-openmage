<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cart Product API
 */
class Toluca_Bot_Model_Cart_Product_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function add ($productId, $productName = null, $qty = 1, $bundle = null, $options = null, $comment = null, $categoryId = null, $categoryName = null)
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId    = Mage::app ()->getStore ()->getId ();
        $categoryId = Mage::app ()->getStore ()->getRootCategoryId ();

        $collection = $this->_getProductCollection ($storeId, $categoryId)
            ->addAttributeToFilter ('sku_position', array ('eq' => $productId))
        ;

        $_productId = $collection->getFirstItem ()->getId ();

        $_product = Mage::getModel ('catalog/product')->load ($_productId);

        if ($_product && $_product->getId ())
        {
            if ($productName != null && !str_contains ($_product->getName (), $productName))
            {
                /*
                $result = Mage::helper ('bot/message')->getProductInvalidIdOrNameText ($productId, $productName, $_product) . PHP_EOL . PHP_EOL
                    . Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                ;

                return $result;
                */

                $collection = $this->_getProductCollection ($storeId, $categoryId)
                    ->addAttributeToFilter ('name', array ('like' => $productName . '%'))
                ;

                $_productId = $collection->getFirstItem ()->getId ();
            }
        }

        $bundleOptions = null;
        $customOptions = null;
        $additionalOptions = null;

        if (is_array ($bundle) && count ($bundle) > 0)
        {
            foreach ($bundle as $bundleItem)
            {
                $bundleId      = $bundleItem ['bundleId'];
                $bundleName    = $bundleItem ['bundleName'];
                $selectionId   = $bundleItem ['selectionId'];
                $selectionName = $bundleItem ['selectionName'];

                $bundleCollection = $this->_getBundleOptionsCollection ($_productId)
                    ->addFieldToFilter ('main_table.position', array ('eq' => $bundleId))
                ;

                $option = $bundleCollection->getFirstItem ();

                if ($option && $option->getId ())
                {
                    $optionTitle = $option->getTitle () ?? $option->getDefaultTitle ();

                    if ($bundleName != null && !str_contains ($optionTitle, $bundleName))
                    {
                        $bundleCollection = $this->_getBundleOptionsCollection ($_productId)
                            ->addFieldToFilter ('option_value.title', array ('like' => $bundleName . '%'))
                        ;

                        $option = $bundleCollection->getFirstItem ();
                    }
                }

                if ($option && $option->getId () > 0)
                {
                    preg_match_all ('/([\d]{1,})/', $selectionId, $matches);

                    $selectionCollection = $this->_getBundleSelectionsCollection ($option);

                    $selectionCollection->getSelect ()
                        ->where ('selection.parent_product_id = ?', $_productId)
                        ->where ('selection.position IN (?)', $matches [0])
                        ->reset (Zend_Db_Select::COLUMNS)
                        ->columns (array(
                            'id'   => 'selection.selection_id',
                            'name' => 'e.name'
                        ))
                    ;

                    if ($selectionCollection->count () > 0)
                    {
                        $selection = $selectionCollection->getFirstItem ();

                        if ($selection && $selection->getData ('id'))
                        {
                            $selectionTitle = $selection->getData ('name');

                            if ($selectionName != null && !str_contains ($selectionTitle, $selectionName))
                            {
                                $selectionCollection = $this->_getBundleSelectionsCollection ($option);

                                $selectionCollection->getSelect ()
                                    ->where ('selection.parent_product_id = ?', $_productId)
                                    ->where ('e.name LIKE ?', $selectionName . '%')
                                    ->reset (Zend_Db_Select::COLUMNS)
                                    ->columns (array(
                                        'id'   => 'selection.selection_id',
                                        'name' => 'e.name'
                                    ))
                                ;
                            }
                        }
                    }

                    if ($selectionCollection->count () > 0)
                    {
                        $bundleOptions [$option->getId ()] = array_keys ($selectionCollection->toOptionHash ());
                    }
                }
                else
                {
                    $options [] = array (
                        'optionId' => $bundleId,    'optionName' => $bundleName,
                        'valueId'  => $selectionId, 'valueName'  => $selectionName,
                    );
                }
            }
        }

        if (is_array ($options) && count ($options) > 0)
        {
            foreach ($options as $optionItem)
            {
                $optionId   = $optionItem ['optionId'];
                $optionName = $optionItem ['optionName'];
                $valueId    = $optionItem ['valueId'];
                $valueName  = $optionItem ['valueName'];

                $optionsCollection = $this->_getProductOptionsCollection ($_productId, $storeId)
                    ->addFieldToFilter ('main_table.sort_order', array ('eq' => $optionId))
                ;

                $option = $optionsCollection->getFirstItem ();

                if ($option && $option->getId ())
                {
                    $optionTitle = $option->getTitle () ?? $option->getDefaultTitle ();

                    if ($optionName != null && !str_contains ($optionTitle, $optionName))
                    {
                        $optionsCollection = $this->_getProductOptionsCollection ($_productId, $storeId)
                            ->addFieldToFilter ('default_option_title.title', array ('like' => $optionName . '%'))
                        ;

                        $option = $optionsCollection->getFirstItem ();
                    }
                }

                if ($option && $option->getId () > 0)
                {
                    preg_match_all ('/([\d]{1,})/', $valueId, $matches);

                    $valuesCollection = $this->_getProductValuesCollection ($option, $storeId)
                        ->addFieldToFilter ('main_table.sort_order', array ('in' => $matches [0]))
                    ;

                    $valuesCollection->getSelect ()
                        ->reset (Zend_Db_Select::COLUMNS)
                        ->columns (array(
                            'id'   => 'main_table.option_type_id',
                            'name' => 'default_value_title.title'
                        ))
                    ;

                    if ($valuesCollection->count () > 0)
                    {
                        $value = $valuesCollection->getFirstItem ();

                        if ($value && $value->getData ('id'))
                        {
                            $valueTitle = $value->getData ('name');

                            if ($valueName != null && !str_contains ($valueTitle, $valueName))
                            {
                                $valuesCollection = $this->_getProductValuesCollection ($option, $storeId)
                                    ->addFieldToFilter ('default_value_title.title', array ('like' => $valueName . '%'))
                                ;

                                $valuesCollection->getSelect ()
                                    ->reset (Zend_Db_Select::COLUMNS)
                                    ->columns (array(
                                        'id'   => 'main_table.option_type_id',
                                        'name' => 'default_value_title.title'
                                    ))
                                ;
                            }
                        }
                    }

                    if ($valuesCollection->count () > 0)
                    {
                        $customOptions [$option->getId ()] = array_keys ($valuesCollection->toOptionHash ());
                    }
                }
            }
        }

        if (!empty ($comment))
        {
            $additionalOptions = array(
                array(
                    'code'  => 'comment',
                    'label' => Mage::helper ('bot')->__('Comment'),
                    'value' => $comment,
                ),
            );
        }

        $productsData = array(
            array(
                'product_id'         => $_productId,
                'qty'                => $qty,
                'bundle_option'      => $bundleOptions,
                'options'            => $customOptions,
                'additional_options' => $additionalOptions,
            )
        );

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            Mage::getModel ('checkout/cart_product_api')->add ($quote->getId (), $productsData, $storeId);

            $chat = $this->_getChat ($botType, $from, $to, $senderName, $senderMessage);

            $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_PRODUCT)
                ->setProductId ($_productId)
                ->setSelections (new Zend_Db_Expr ('NULL'))
                ->setOptions (new Zend_Db_Expr ('NULL'))
                ->setComment (new Zend_Db_Expr ('NULL'))
                ->setUpdatedAt (date ('c'))
            ;

            if (!empty($bundleOptions))
            {
                $chat->setSelections(json_encode($bundleOptions));
            }

            if (!empty($customOptions))
            {
                $chat->setOptions(json_encode($customOptions));
            }

            if (!empty($comment))
            {
                $chat->setComment($comment);
            }

            $chat->save ();

            $result = Mage::helper ('bot/message')->getProductAddedToCartText () . PHP_EOL . PHP_EOL
                . $this->_getCartReview ($quote->getId (), $storeId) . PHP_EOL . PHP_EOL
            ;
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                . Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL
            ;
        }

        return $result;
    }
}

