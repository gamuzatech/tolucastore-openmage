<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Chat API
 */
class Toluca_Bot_Model_Chat_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function message ($botType, $from, $to, $senderName, $senderMessage)
    {
        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);
/*
        if (strpos ($to, $this->_phone) === false)
        {
            return array ('text' => '[ WRONG NUMBER ]');
        }
*/
        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $collection = Mage::getModel ('bot/chat')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            /*
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            */
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            /*
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
            */
        ;

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        if ($collection->count () > 0)
        {
            $chat = $collection->getFirstItem ();

            $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

            if (!$quote || !$quote->getId ())
            {
                foreach ($collection as $key => $item)
                {
                    $collection->removeItemByKey ($key);
                }

                $chat->delete ();
            }
        }

        if (!empty ($senderName))
        {
             $senderName = explode (' ', $senderName, 2);
        }

        if (is_array ($senderName) && count ($senderName) == 1)
        {
            $senderName [1] = '------';
        }

        if (!$senderName || !is_array ($senderName) || count ($senderName) != 2)
        {
            $senderName = array(
                0 => Mage::helper ('bot')->__('Firstname'),
                1 => Mage::helper ('bot')->__('Lastname'),
            );
        }

        $shippingPostcode = preg_replace ('[\D]', null, Mage::getStoreConfig ('shipping/origin/postcode', $storeId));

        $remoteIp = Mage::helper ('bot')->getRemoteIp ();

        if (!$collection->count ())
        {
            $quote = Mage::getModel ('sales/quote')
                ->setStoreId ($storeId)
                ->setIsActive (true)
                ->setIsMultiShipping (false)
                ->setRemoteIp ($remoteIp)
                ->setCustomerCellphone ($from)
                ->setCustomerFirstname ($senderName [0])
                ->setCustomerLastname ($senderName [1])
                ->setCustomerEmail (self::DEFAULT_CUSTOMER_EMAIL)
                ->setCustomerTaxvat (self::DEFAULT_CUSTOMER_TAXVAT)
                ->save ()
            ;

            $chat = Mage::getModel ('bot/chat')
                ->setIsActive (true)
                ->setTypeId ($botType)
                ->setStoreId ($storeId)
                ->setQuoteId ($quote->getId ())
                ->setNumber ($from)
                ->setPhone ($to)
                ->setFirstname ($senderName [0])
                ->setLastname ($senderName [1])
                ->setRemoteIp ($remoteIp)
                ->setEmail (self::DEFAULT_CUSTOMER_EMAIL)
                ->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                ->setCreatedAt (date ('c'))
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;

            $this->_saveMessage ($senderMessage, $chat, Toluca_Bot_Helper_Data::MESSAGE_TYPE_QUESTION);

            $customerData = array(
                'mode'      => Mage_Checkout_Model_Type_Onepage::METHOD_GUEST,
                'firstname' => $senderName [0],
                'lastname'  => $senderName [1],
                'email'     => self::DEFAULT_CUSTOMER_EMAIL,
                'taxvat'    => self::DEFAULT_CUSTOMER_TAXVAT,
            );

            Mage::getModel ('checkout/cart_customer_api')->set ($quote->getId (), $customerData, $storeId);

            $quote->setData (Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_IS_BOT, true)
                ->setData (Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_BOT_TYPE, $botType)
                ->setCustomerGroupId (0)
                ->setCustomerIsGuest (1)
                ->save ()
            ;

            Mage::getModel ('checkout/cart_customer_api')->setAddresses ($quote->getId (), array(
                array(
                    'mode'       => 'billing',
                    'firstname'  => $senderName [0],
                    'lastname'   => $senderName [1],
                    'street'     => array (
                        Mage::getStoreConfig ('shipping/origin/street_line1', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line2', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line3', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line4', $storeId),
                    ),
                    'city'       => Mage::getStoreConfig ('shipping/origin/city',      $storeId),
                    'region'     => Mage::getStoreConfig ('shipping/origin/region_id', $storeId),
                    'country_id' => Mage::getStoreConfig ('shipping/origin/country_id', $storeId),
                    'postcode'   => $shippingPostcode,
                    'cellphone'  => substr ($from, -13),
                    'use_for_shipping' => 1,
                )
            ), $storeId);

            $result = Mage::helper ('bot/message')->getGreetingText (implode (' ', $senderName)) . PHP_EOL . PHP_EOL
                . Mage::helper ('bot/message')->getWelcomeText () . PHP_EOL . PHP_EOL
                . $this->_getCategoryList ($storeId)
            ;

            $this->_saveMessage ($result, $chat);

            return array ('text' => $result);
        }

        $chat = $collection->getFirstItem ();

        // $chat->setRemoteIp ($remoteIp)->save ();

        $this->_saveMessage ($senderMessage, $chat, Toluca_Bot_Helper_Data::MESSAGE_TYPE_QUESTION);

        if ($chat->getIsMuted ())
        {
            return array ('text' => '', 'muted' => 1);
        }

        $body = Mage::helper ('core')->removeAccents ($senderMessage);

        $result = null;

        if (!strcmp (strtolower (trim ($body)), Toluca_Bot_Helper_Data::STATUS_ZAP))
        {
            $chat->setIsMuted (true)
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;

            $result = Mage::helper ('bot/message')->getPleaseWaitAnAttendantText ();

            $this->_saveMessage ($result, $chat);

            return array ('text' => $result, 'muted' => 1);
        }

        switch ($chat->getStatus ())
        {
            case Toluca_Bot_Helper_Data::STATUS_CATEGORY:
            {
                $categoryId = intval ($body);

                $collection = $this->_getCategoryCollection ($storeId)
                    ->addFieldToFilter ('main_table.position', array ('eq' => $categoryId))
                ;

                $categoryId = $collection->getFirstItem ()->getId ();

                if ($collection->count () > 0)
                {
                    $chat->setCategoryId ($categoryId)
                        ->setStatus (Toluca_Bot_Helper_Data::STATUS_PRODUCT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    $result = $this->_getProductList ($storeId, $categoryId);
                }
                else
                {
                    $result = $this->_getCategoryList ($storeId);
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_PRODUCT:
            {
                $info = Mage::getModel ('checkout/cart_api')->info ($chat->getQuoteId (), $storeId);

                if (!strcmp (strtolower (trim ($body)), self::COMMAND_OK) && count ($info ['items']) > 0)
                {
                    $result = $this->_getCartReview ($chat->getQuoteId (), $storeId)
                        . Mage::helper ('bot/message')->getTypeListToCategoriesText (self::COMMAND_ZERO) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getTypeClearToRestartText (self::COMMAND_ONE) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                    ;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CART)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                if (!strcmp (strtolower (trim ($body)), self::COMMAND_ZERO))
                {
                    $result = $this->_getCategoryList ($storeId);

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                $productId = intval ($body);

                $category = Mage::getModel ('catalog/category')->load ($chat->getCategoryId ());

                $collection = $this->_getProductCollection ($storeId, $category->getId ())
                    ->addAttributeToFilter ('sku_position', array ('eq' => $productId))
                ;

                $productId = $collection->getFirstItem ()->getId ();

                $product = Mage::getModel ('catalog/product')->load ($productId);

                if ($product && $product->getId () > 0 && in_array ($storeId, $product->getStoreIds ()))
                {
                    $chat->setProductId ($product->getId ())
                        ->setSelections (new Zend_Db_Expr ('NULL'))
                        ->setOptions (new Zend_Db_Expr ('NULL'))
                        ->setComment (new Zend_Db_Expr ('NULL'))
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    if (!strcmp ($product->getTypeId (), Mage_Catalog_Model_Product_Type::TYPE_BUNDLE))
                    {
                        $result = $this->_getBundleOptions ($product->getId ());

                        $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_BUNDLE)
                            ->setUpdatedAt (date ('c'))
                            ->save ()
                        ;

                        break;
                    }

                    if (!empty ($product->getOptions ()))
                    {
                        $result = $this->_getProductOptions ($product->getId ());

                        $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_OPTION)
                            ->setUpdatedAt (date ('c'))
                            ->save ()
                        ;
                    }
                    else
                    {
                        $productsData = array(
                            array(
                                'product_id' => $chat->getProductId (),
                            )
                        );

                        try
                        {
                            Mage::getModel ('checkout/cart_product_api')->add ($chat->getQuoteId (), $productsData, $storeId);

                            $result = Mage::helper ('bot/message')->getProductAddedToCartText () . PHP_EOL . PHP_EOL
                                . $this->_getCartReview ($chat->getQuoteId (), $storeId)
                                . Mage::helper ('bot/message')->getTypeListToCategoriesText (self::COMMAND_ZERO) . PHP_EOL . PHP_EOL
                                . Mage::helper ('bot/message')->getTypeClearToRestartText (self::COMMAND_ONE) . PHP_EOL . PHP_EOL
                                . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                            ;

                            $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CART)
                                ->setUpdatedAt (date ('c'))
                                ->save ()
                            ;
                        }
                        catch (Mage_Api_Exception $e)
                        {
                            $result = Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                                . $e->getCustomMessage () . PHP_EOL . PHP_EOL
                                . $this->_getProductList ($storeId, $chat->getCategoryId ())
                            ;

                            $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_PRODUCT)
                                ->setUpdatedAt (date ('c'))
                                ->save ()
                            ;
                        }
                    }
                }
                else
                {
                    $result = $this->_getProductList ($storeId, $chat->getCategoryId ());

                    $info = Mage::getModel ('checkout/cart_api')->info ($chat->getQuoteId (), $storeId);

                    if (count ($info ['items']) > 0)
                    {
                        $result .= PHP_EOL . PHP_EOL . Mage::helper ('bot/message')->getTypeCommandToGoToCartText (self::COMMAND_OK);
                    }
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_BUNDLE:
            {
                if (!strcmp (strtolower (trim ($body)), self::COMMAND_ZERO))
                {
                    $chatStatus = null;

                    $product = Mage::getModel ('catalog/product')->load ($chat->getProductId ());

                    if (!empty ($product->getOptions ()))
                    {
                        $result = $this->_getProductOptions ($product->getId ());

                        $chatStatus = Toluca_Bot_Helper_Data::STATUS_OPTION;
                    }
                    else
                    {
                        $result = Mage::helper ('bot/message')->getAddCommentForProductText ();

                        $chatStatus = Toluca_Bot_Helper_Data::STATUS_COMMENT;
                    }

                    $chat->setStatus ($chatStatus)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                $optionId = intval ($body);

                $collection = Mage::getModel ('bundle/option')->getCollection ()
                    ->joinValues (Mage_Core_Model_App::ADMIN_STORE_ID)
                    ->setProductIdFilter ($chat->getProductId ())
                    ->addFieldToFilter ('main_table.position', array ('eq' => $optionId))
                ;

                $option = $collection->getFirstItem ();

                if ($option && $option->getId () > 0)
                {
                    $result = $this->_getBundleSelections ($option);

                    $chat->setBundleId ($option->getId ())
                        ->setStatus (Toluca_Bot_Helper_Data::STATUS_SELECTION)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;
                }
                else
                {
                    $result = $this->_getBundleOptions ($chat->getProductId ());
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_SELECTION:
            {
                preg_match_all ('/([\d]{1,})/', $body, $matches);

                $collection = Mage::getModel ('bundle/selection')->getCollection ()
                    ->addAttributeToFilter ('name', array ('notnull' => true))
                    ->setOptionIdsFilter ($chat->getBundleId ())
                ;

                $collection->getSelect ()
                    ->where ('selection.parent_product_id = ?', $chat->getProductId ())
                    ->where ('selection.position IN (?)', $matches [0])
                    ->reset (Zend_Db_Select::COLUMNS)
                    ->columns (array(
                        'id'   => 'selection.selection_id',
                        'name' => 'e.name'
                    ))
                ;

                if ($collection->count () > 0 || !strcmp (strtolower (trim ($body)), self::COMMAND_ZERO))
                {
                    $product = Mage::getModel ('catalog/product')->load ($chat->getProductId ());

                    $result = $this->_getBundleOptions ($chat->getProductId ());

                    $productSelections = json_decode ($chat->getSelections (), true);

                    $productSelections [$chat->getBundleId ()] = array_keys ($collection->toOptionHash ());

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_BUNDLE)
                        ->setSelections (json_encode ($productSelections))
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    $collection = Mage::getModel ('bundle/option')->getCollection ()
                        ->setProductIdFilter ($product->getId ())
                        ->joinValues (Mage_Core_Model_App::ADMIN_STORE_ID)
                        ->setPositionOrder ()
                    ;

                    if ($collection->clear ()->count () == count ($productSelections))
                    {
                        $body = self::COMMAND_ZERO;

                        goto __productComment;
                    }

                    break;
                }
                else
                {
                    $collection = Mage::getModel ('bundle/option')->getCollection ()
                        ->setIdFilter ($chat->getBundleId ())
                        ->setProductIdFilter ($chat->getProductId ())
                        ->joinValues (Mage_Core_Model_App::ADMIN_STORE_ID)
                    ;

                    $option = $collection->getFirstItem ();

                    $result = $this->_getBundleSelections ($option);
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_OPTION:
            {
                if (!strcmp (strtolower (trim ($body)), self::COMMAND_ZERO) && $this->_productComment)
                {
                    $result = Mage::helper ('bot/message')->getAddCommentForProductText ();

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_COMMENT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }
                else if (!strcmp (strtolower (trim ($body)), self::COMMAND_ZERO) && !$this->_productComment)
                {
                    goto __productComment;
                }

                $optionId = intval ($body);

                $collection = Mage::getModel ('catalog/product_option')->getCollection ()
                    ->addFieldToFilter ('main_table.product_id', array ('eq' => $chat->getProductId ()))
                    ->addFieldToFilter ('main_table.sort_order', array ('eq' => $optionId))
                    ->addTitleToResult ($storeId)
                    ->addValuesToResult ($storeId)
                ;

                $option = $collection->getFirstItem ();

                if ($option && $option->getId () > 0)
                {
                    $chat->setOptionId ($option->getId ())
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    $result = $this->_getProductValues ($option);

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_VALUE)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;
                }
                else
                {
                    $product = Mage::getModel ('catalog/product')->load ($chat->getProductId ());

                    $result = $this->_getProductOptions ($product->getId ());
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_VALUE:
            {
                preg_match_all ('/([\d]{1,})/', $body, $matches);

                $collection = Mage::getModel ('catalog/product_option_value')->getCollection ()
                    ->addFieldToFilter ('main_table.sort_order', array ('in' => $matches [0]))
                    ->addTitleToResult ($storeId)
                    ->addPriceToResult ($storeId)
                    ->addOptionToFilter ($chat->getOptionId ())
                    // ->getValuesByOption ($matches [0])
                ;

                $collection->getSelect ()
                    ->reset (Zend_Db_Select::COLUMNS)
                    ->columns (array(
                        'id'   => 'main_table.option_type_id',
                        'name' => 'default_value_title.title'
                    ))
                ;

                if ($collection->count () > 0 || !strcmp (strtolower (trim ($body)), self::COMMAND_ZERO))
                {
                    $product = Mage::getModel ('catalog/product')->load ($chat->getProductId ());

                    $result = $this->_getProductOptions ($product->getId ());

                    $productOptions = json_decode ($chat->getOptions (), true);

                    $productOptions [$chat->getOptionId ()] = array_keys ($collection->toOptionHash ());

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_OPTION)
                        ->setOptions (json_encode ($productOptions))
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }
                else
                {
                    $collection = Mage::getModel ('catalog/product_option')->getCollection ()
                        ->addFieldToFilter ('main_table.option_id', array ('eq' => $chat->getOptionId ()))
                        ->addTitleToResult ($storeId)
                        ->addValuesToResult ($storeId)
                    ;

                    $option = $collection->getFirstItem ();

                    $result = $this->_getProductValues ($option);
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_COMMENT:
            {
                __productComment:

                $additionalOptions = null;

                if (strcmp (strtolower (trim ($body)), self::COMMAND_ZERO) != 0)
                {
                    $additionalOptions = array(
                        array(
                            'code'  => 'comment',
                            'label' => Mage::helper ('bot')->__('Comment'),
                            'value' => $body,
                        )
                    );
                }

                $productsData = array(
                    array(
                        'product_id'         => $chat->getProductId (),
                        'bundle_option'      => json_decode ($chat->getSelections (), true),
                        'options'            => json_decode ($chat->getOptions (), true),
                        'additional_options' => $additionalOptions,
                    )
                );

                try
                {
                    Mage::getModel ('checkout/cart_product_api')->add ($chat->getQuoteId (), $productsData, $storeId);

                    $result = Mage::helper ('bot/message')->getProductAddedToCartText () . PHP_EOL . PHP_EOL
                        . $this->_getCartReview ($chat->getQuoteId (), $storeId)
                        . Mage::helper ('bot/message')->getTypeListToCategoriesText (self::COMMAND_ZERO) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getTypeClearToRestartText (self::COMMAND_ONE) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                    ;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CART)
                        ->setComment ($body)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;
                }
                catch (Mage_Api_Exception $e)
                {
                    $result = Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL
                        . $this->_getProductList ($storeId, $chat->getCategoryId ())
                    ;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_PRODUCT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_CART:
            {
                if (!strcmp (strtolower (trim ($body)), self::COMMAND_ZERO))
                {
                    $result = $this->_getCategoryList ($storeId);

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                if (!strcmp (strtolower (trim ($body)), self::COMMAND_ONE))
                {
                    $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

                    foreach ($quote->getAllItems () as $item)
                    {
                        $item->delete ();
                    }

                    $result = $this->_getCategoryList ($storeId);

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                if (!strcmp (strtolower (trim ($body)), self::COMMAND_OK))
                {
                    $result = Mage::helper ('bot/message')->getPleaseEnterTheAddressText ();

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_ADDRESS)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    break;
                }

                $result = $this->_getCartReview ($chat->getQuoteId (), $storeId)
                    . Mage::helper ('bot/message')->getTypeListToCategoriesText (self::COMMAND_ZERO) . PHP_EOL . PHP_EOL
                    . Mage::helper ('bot/message')->getTypeClearToRestartText (self::COMMAND_ONE) . PHP_EOL . PHP_EOL
                    . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                ;

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_ADDRESS:
            {
                $street = Mage::helper ('core')->removeAccents ($body);

                if (preg_match ('/(.*)\s([\d]{1,})\s(.*)/', $street, $matches) == '1'
                    || preg_match ('/(.*)\s([\d]{1,})/', $street, $matches) == '1'
                    || preg_match ('/(.*)/', $street, $matches) == '1')
                {
                    $streetNumber   = !empty ($matches [2]) ? $matches [2] : '------';
                    $streetDistrict = !empty ($matches [3]) ? $matches [3] : '------';

                    Mage::getModel ('checkout/cart_customer_api')->setAddresses ($chat->getQuoteId (), array(
                        array(
                            'mode'       => 'billing',
                            'firstname'  => $senderName [0],
                            'lastname'   => $senderName [1],
                            'street'     => array ($matches [1], $streetNumber, null, $streetDistrict),
                            'city'       => Mage::getStoreConfig ('shipping/origin/city', $storeId),
                            'region'     => Mage::getStoreConfig ('shipping/origin/region_id', $storeId),
                            'country_id' => Mage::getStoreConfig ('shipping/origin/country_id', $storeId),
                            'postcode'   => $shippingPostcode,
                            'cellphone'  => substr ($chat->getNumber (), -13),
                            'use_for_shipping' => 1,
                        )
                    ), $storeId);

                    $shippingMethods = Mage::getModel ('checkout/cart_shipping_api')->getShippingMethodsList ($chat->getQuoteId (), $storeId);

                    if (count ($shippingMethods) > 0)
                    {
                        $result = Mage::helper ('bot/message')->getEnterDeliveryMethodText () . PHP_EOL . PHP_EOL;

                        foreach ($this->_shippingMethods as $_id => $_method)
                        {
                            foreach ($shippingMethods as $method)
                            {
                                if (!strcmp ($method ['code'], $_method))
                                {
                                    $strLen = self::SHIPPING_ID_LENGTH - strlen ($_id);
                                    $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                                    $shippingPrice = Mage::helper ('core')->currency ($method ['price'], true, false);

                                    $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['method_title'], $shippingPrice) . PHP_EOL;
                                }
                            }
                        }

                        $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_SHIPPING)
                            ->setUpdatedAt (date ('c'))
                            ->save ()
                        ;
                    }
                    else
                    {
                        $result = Mage::helper ('bot/message')->getNoDeliveryMethodFoundText () . PHP_EOL . PHP_EOL;

                        $result .= Mage::helper ('bot/message')->getPleaseEnterTheAddressText ();
                    }
                }
                else
                {
                    $result = Mage::helper ('bot/message')->getPleaseEnterTheAddressText ();
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_SHIPPING:
            {
                $shippingId = intval ($body);

                $shippingMethods = Mage::getModel ('checkout/cart_shipping_api')->getShippingMethodsList ($chat->getQuoteId (), $storeId);

                foreach ($shippingMethods as $id => $method)
                {
                    if (!in_array ($method ['code'], $this->_shippingMethods))
                    {
                        unset ($shippingMethods [$id]);
                    }
                }

                if (!empty ($this->_shippingMethods [$shippingId]) && $this->_getAllowedShipping ($shippingMethods, $shippingId))
                {
                    Mage::getModel ('checkout/cart_shipping_api')->setShippingMethod ($chat->getQuoteId (), $this->_shippingMethods [$shippingId], $storeId);

                    $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($chat->getQuoteId (), $storeId);

                    if (count ($paymentMethods) > 0)
                    {
                        $result = Mage::helper ('bot/message')->getEnterPaymentMethodText () . PHP_EOL . PHP_EOL;

                        $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

                        foreach ($this->_paymentMethods as $_id => $_method)
                        {
                            foreach ($paymentMethods as $method)
                            {
                                if (!strcmp ($method ['code'], $_method))
                                {
                                    $strLen = self::PAYMENT_ID_LENGTH - strlen ($_id);
                                    $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                                    $paymentPrice = Mage::helper ('core')->currency ($quote->getBaseGrandTotal (), true, false);

                                    $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['title'], $paymentPrice) . PHP_EOL;
                                }
                            }
                        }

                        $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_PAYMENT)
                            ->setUpdatedAt (date ('c'))
                            ->save ()
                        ;
                    }
                    else
                    {
                        $result = Mage::helper ('bot/message')->getNoPaymentMethodFoundText () . PHP_EOL . PHP_EOL
                            . Mage::helper ('bot/message')->getEnterDeliveryMethodText () . PHP_EOL . PHP_EOL
                        ;

                        foreach ($this->_shippingMethods as $_id => $_method)
                        {
                            foreach ($shippingMethods as $method)
                            {
                                if (!strcmp ($method ['code'], $_method))
                                {
                                    $strLen = self::SHIPPING_ID_LENGTH - strlen ($_id);
                                    $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                                    $shippingPrice = Mage::helper ('core')->currency ($method ['price'], true, false);

                                    $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['method_title'], $shippingPrice) . PHP_EOL;
                                }
                            }
                        }
                    }
                }
                else
                {
                    $result = Mage::helper ('bot/message')->getEnterDeliveryMethodText () . PHP_EOL . PHP_EOL;

                    foreach ($this->_shippingMethods as $_id => $_method)
                    {
                        foreach ($shippingMethods as $method)
                        {
                            if (!strcmp ($method ['code'], $_method))
                            {
                                $strLen = self::SHIPPING_ID_LENGTH - strlen ($_id);
                                $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                                $shippingPrice = Mage::helper ('core')->currency ($method ['price'], true, false);

                                $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['method_title'], $shippingPrice) . PHP_EOL;
                            }
                        }
                    }
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_PAYMENT:
            {
                $paymentId = intval ($body);

                $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($chat->getQuoteId (), $storeId);

                foreach ($paymentMethods as $id => $method)
                {
                    if (!in_array ($method ['code'], $this->_paymentMethods))
                    {
                        unset ($paymentMethods [$id]);
                    }
                }

                if (!empty ($this->_paymentMethods [$paymentId]) && $this->_getAllowedPayment ($paymentMethods, $paymentId))
                {
                    switch ($this->_paymentMethods [$paymentId])
                    {
                        case 'cashondelivery':
                        {
                            $result = Mage::helper ('bot/message')->getEnterAmountForMoneyChangeText () . PHP_EOL . PHP_EOL
                                . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                            ;

                            $chatStatus = Toluca_Bot_Helper_Data::STATUS_PAYMENT_CASH;

                            break;
                        }
                        case 'machineondelivery':
                        {
                            $result = $this->_getCardList ($chat->getQuoteId (), $storeId);

                            $chatStatus = Toluca_Bot_Helper_Data::STATUS_PAYMENT_MACHINE;

                            break;
                        }
                        case 'gamuza_pagcripto_payment':
                        {
                            $result = $this->_getCriptoList ($chat->getQuoteId (), $storeId);

                            $chatStatus = Toluca_Bot_Helper_Data::STATUS_PAYMENT_CRIPTO;

                            break;
                        }
                        default:
                        {
                            $paymentData = array(
                                'method' => $this->_paymentMethods [$paymentId]
                            );

                            Mage::getModel ('bot/checkout_cart_payment_api')->setPaymentMethod ($chat->getQuoteId (), $paymentData, $storeId);

                            $result = $this->_getCheckoutReview ($chat->getQuoteId (), $storeId) . PHP_EOL . PHP_EOL;

                            $chatStatus = Toluca_Bot_Helper_Data::STATUS_CHECKOUT;

                            break;
                        }
                    }

                    $chat->setStatus ($chatStatus)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    if ($chatStatus == Toluca_Bot_Helper_Data::STATUS_CHECKOUT && !$this->_orderReview)
                    {
                        $body = self::COMMAND_OK;

                        goto __checkoutCreateOrder;
                    }
                }
                else
                {
                    $result = Mage::helper ('bot/message')->getEnterPaymentMethodText () . PHP_EOL . PHP_EOL;

                    $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

                    foreach ($this->_paymentMethods as $_id => $_method)
                    {
                        foreach ($paymentMethods as $method)
                        {
                            if (!strcmp ($method ['code'], $_method))
                            {
                                $strLen = self::PAYMENT_ID_LENGTH - strlen ($_id);
                                $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                                $paymentPrice = Mage::helper ('core')->currency ($quote->getBaseGrandTotal (), true, false);

                                $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['title'], $paymentPrice) . PHP_EOL;
                            }
                        }
                    }
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_PAYMENT_CASH:
            {
                $paymentChange  = intval ($body);
                $paymentCommand = strtolower (trim ($body));

                $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

                if ($paymentChange > $quote->getBaseGrandTotal () || !strcmp ($paymentCommand, self::COMMAND_OK))
                {
                    $paymentData = array(
                        'method'      => 'cashondelivery',
                        'change_type' => $paymentChange ? '1' : '0',
                        'cash_amount' => $paymentChange,
                    );

                    Mage::getModel ('bot/checkout_cart_payment_api')->setPaymentMethod ($chat->getQuoteId (), $paymentData, $storeId);

                    $result = $this->_getCheckoutReview ($chat->getQuoteId (), $storeId) . PHP_EOL . PHP_EOL;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CHECKOUT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    if (!$this->_orderReview)
                    {
                        $body = self::COMMAND_OK;

                        goto __checkoutCreateOrder;
                    }
                }
                else
                {
                    $result = Mage::helper ('bot/message')->getEnterAmountForMoneyChangeText () . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_OK)
                    ;
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_PAYMENT_MACHINE:
            {
                $paymentId = intval ($body);

                $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($chat->getQuoteId (), $storeId);

                foreach ($paymentMethods as $id => $method)
                {
                    if (strcmp ($method ['code'], 'machineondelivery') != 0)
                    {
                        unset ($paymentMethods [$id]);
                    }
                }

                if (!empty ($this->_paymentCcTypes [$paymentId]) && $this->_getAllowedCcType ($paymentMethods, $paymentId))
                {
                    $paymentData = array(
                        'method'  => 'machineondelivery',
                        'cc_type' => $this->_paymentCcTypes [$paymentId],
                    );

                    Mage::getModel ('bot/checkout_cart_payment_api')->setPaymentMethod ($chat->getQuoteId (), $paymentData, $storeId);

                    $result = $this->_getCheckoutReview ($chat->getQuoteId (), $storeId) . PHP_EOL . PHP_EOL;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CHECKOUT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    if (!$this->_orderReview)
                    {
                        $body = self::COMMAND_OK;

                        goto __checkoutCreateOrder;
                    }
                }
                else
                {
                    $result = $this->_getCardList ($chat->getQuoteId (), $storeId);
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_PAYMENT_CRIPTO:
            {
                $paymentId = intval ($body);

                $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($chat->getQuoteId (), $storeId);

                foreach ($paymentMethods as $id => $method)
                {
                    if (strcmp ($method ['code'], 'gamuza_pagcripto_payment') != 0)
                    {
                        unset ($paymentMethods [$id]);
                    }
                }

                if (!empty ($this->_paymentCriptoTypes [$paymentId]) && $this->_getAllowedCriptoType ($paymentMethods, $paymentId))
                {
                    $paymentData = array(
                        'method'  => 'gamuza_pagcripto_payment',
                        'cc_type' => $this->_paymentCriptoTypes [$paymentId],
                    );

                    Mage::getModel ('bot/checkout_cart_payment_api')->setPaymentMethod ($chat->getQuoteId (), $paymentData, $storeId);

                    $result = $this->_getCheckoutReview ($chat->getQuoteId (), $storeId) . PHP_EOL . PHP_EOL;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_CHECKOUT)
                        ->setUpdatedAt (date ('c'))
                        ->save ()
                    ;

                    if (!$this->_orderReview)
                    {
                        $body = self::COMMAND_OK;

                        goto __checkoutCreateOrder;
                    }
                }
                else
                {
                    $result = $this->_getCriptoList ($chat->getQuoteId (), $storeId);
                }

                break;
            }
            case Toluca_Bot_Helper_Data::STATUS_CHECKOUT:
            {
                __checkoutCreateOrder:

                if (!strcmp (strtolower (trim ($body)), self::COMMAND_OK))
                {
                    Mage::app ()->getStore ()->setConfig (Mage_Checkout_Helper_Data::XML_PATH_GUEST_CHECKOUT, '1');

                    try
                    {
                        $incrementId = Mage::getModel ('checkout/cart_api')->createOrder ($chat->getQuoteId (), $storeId);
                    }
                    catch (Exception $e)
                    {
                        $result = $e->getMessage ();

                        break;
                    }

                    $storeName = Mage::getStoreConfig (Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);

                    $order = Mage::getModel ('sales/order')->loadByIncrementId ($incrementId);

                    $result .= Mage::helper ('bot/message')->getYourOrderNumberText ($order) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getOrderInformationText ($order)
                        . Mage::helper ('bot/message')->getThankYouForShoppingText ($storeName) . PHP_EOL . PHP_EOL
                        . Mage::helper ('bot/message')->getBuyThroughTheAppText ()
                    ;

                    $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_ORDER)
                        ->setOrderId ($order->getId ())
                        ->setUpdatedAt (date ('c'))
                        ->setIsActive (false)
                        ->save ()
                    ;

                    $quote = Mage::getModel ('sales/quote')->load ($chat->getQuoteId ());

                    if ($quote && $quote->getId ()) $quote->delete (); // discard
                }
                else
                {
                    $result = $this->_getCheckoutReview ($chat->getQuoteId (), $storeId);
                }

                break;
            }
            default:
            {
                $result = '[ WHERE I AM? ]';

                break;
            }
        }

        $this->_saveMessage ($result, $chat);

        return array ('text' => $result);
    }

    protected function _getCategoryList ($storeId)
    {
        $result = Mage::helper ('bot/message')->getEnterCategoryCodeText () . PHP_EOL . PHP_EOL;

        $result .= parent::_getCategoryList ($storeId);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getEnterZapToAttendantText ();

        return $result;
    }

    protected function _getProductList ($storeId, $categoryId)
    {
        $category = Mage::getModel ('catalog/category')->load ($categoryId);

        $result = Mage::helper ('bot/message')->getProductsForCategoryText ($category->getName ()) . PHP_EOL . PHP_EOL
            . Mage::helper ('bot/message')->getEnterProductCodeToCartText () . PHP_EOL . PHP_EOL
        ;

        $result .= parent::_getProductList ($storeId, $categoryId);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getTypeListToCategoriesText (self::COMMAND_ZERO);

        return $result;
    }

    protected function _getBundleOptions ($productId)
    {
        $result = Mage::helper ('bot/message')->getChooseOptionForProductText () . PHP_EOL . PHP_EOL
            . Mage::helper ('bot/message')->getEnterProductOptionCodeText () . PHP_EOL . PHP_EOL
        ;

        $result .= parent::_getBundleOptions ($productId);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_ZERO);

        return $result;
    }

    protected function _getBundleSelections ($option)
    {
        if (!strcmp ($option->getType (), 'checkbox'))
        {
            $result = Mage::helper ('bot/message')->getEnterValuesCodesToOptionText ($option->getTitle ()) . PHP_EOL . PHP_EOL;
        }
        else if (!strcmp ($option->getType (), 'select'))
        {
            $result = Mage::helper ('bot/message')->getEnterOneValueCodeToOptionText ($option->getTitle ()) . PHP_EOL . PHP_EOL;
        }

        $result .= parent::_getBundleSelections ($option);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_ZERO);

        return $result;
    }

    protected function _getProductOptions ($productId)
    {
        $result = Mage::helper ('bot/message')->getChooseOptionForProductText () . PHP_EOL . PHP_EOL
            . Mage::helper ('bot/message')->getEnterProductOptionCodeText () . PHP_EOL . PHP_EOL
        ;

        $result .= parent::_getProductOptions ($productId);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_ZERO);

        return $result;
    }

    protected function _getProductValues ($option)
    {
        if (!strcmp ($option->getType (), 'checkbox'))
        {
            $result = Mage::helper ('bot/message')->getEnterValuesCodesToOptionText ($option->getTitle ()) . PHP_EOL . PHP_EOL;
        }
        else if (!strcmp ($option->getType (), 'drop_down'))
        {
            $result = Mage::helper ('bot/message')->getEnterOneValueCodeToOptionText ($option->getTitle ()) . PHP_EOL . PHP_EOL;
        }

        $result .= parent::_getProductValues ($option);

        $result .= PHP_EOL . Mage::helper ('bot/message')->getTypeCommandToContinueText (self::COMMAND_ZERO);

        return $result;
    }
}

