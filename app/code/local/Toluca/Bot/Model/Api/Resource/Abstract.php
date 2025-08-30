<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * API Resource Abstract
 */
class Toluca_Bot_Model_Api_Resource_Abstract extends Mage_Api_Model_Resource_Abstract
{
    const CATEGORY_ID_LENGTH = Toluca_Bot_Helper_Data::CATEGORY_ID_LENGTH;
    const PRODUCT_ID_LENGTH  = Toluca_Bot_Helper_Data::PRODUCT_ID_LENGTH;
    const OPTION_ID_LENGTH   = Toluca_Bot_Helper_Data::OPTION_ID_LENGTH;
    const VALUE_ID_LENGTH    = Toluca_Bot_Helper_Data::VALUE_ID_LENGTH;
    const SHIPPING_ID_LENGTH = Toluca_Bot_Helper_Data::SHIPPING_ID_LENGTH;
    const PAYMENT_ID_LENGTH  = Toluca_Bot_Helper_Data::PAYMENT_ID_LENGTH;
    const CCTYPE_ID_LENGTH   = Toluca_Bot_Helper_Data::CCTYPE_ID_LENGTH;
    const QUANTITY_LENGTH    = Toluca_Bot_Helper_Data::QUANTITY_LENGTH;

    const COMMAND_ZERO = Toluca_Bot_Helper_Data::COMMAND_ZERO;
    const COMMAND_ONE  = Toluca_Bot_Helper_Data::COMMAND_ONE;
    const COMMAND_OK   = Toluca_Bot_Helper_Data::COMMAND_OK;

    const DEFAULT_CUSTOMER_EMAIL  = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_EMAIL;
    const DEFAULT_CUSTOMER_TAXVAT = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_TAXVAT;

    protected $_shippingMethods = array(
        '1' => 'pickup_store',
        '2' => 'eatin_local',
        '3' => 'freeshipping_freeshipping',
        '4' => 'flatrate_flatrate',
        '5' => 'tablerate_bestway',

        '6'  => 'pedroteixeira_correios_10065',
        '7'  => 'pedroteixeira_correios_04510',
        '8'  => 'pedroteixeira_correios_04014',
        '9'  => 'pedroteixeira_correios_40290',
        '10' => 'pedroteixeira_correios_04162',

        '11' => 'pedroteixeira_correios_04669',
        '12' => 'pedroteixeira_correios_04693',
        '13' => 'pedroteixeira_correios_40215',
        '14' => 'pedroteixeira_correios_40045',
    );

    protected $_paymentMethods = array(
        '1' => 'cashondelivery',
        '2' => 'machineondelivery',
        '3' => 'banktransfer',
        '4' => 'checkmo',

        '5' => 'gamuza_pagcripto_payment',
        '6' => 'gamuza_picpay_payment',
        '7' => 'gamuza_openpix_payment',

        '8' => 'pagseguropro_boleto',

        '9' => 'free',

        '10' => 'gamuza_brazil_pix',
    );

    protected $_paymentCcTypes = array(
        '1'  => 'AE',
        '2'  => 'AL',
        '3'  => 'AU',
        '4'  => 'BC',
        '5'  => 'CC',
        '6'  => 'DC',
        '7'  => 'DI',
        '8'  => 'EC',
        '9'  => 'ED',
        '10' => 'ELO',
        '11' => 'HI',
        '12' => 'HC',
        '13' => 'JCB',
        '14' => 'MC',
        '15' => 'SM',
        '16' => 'SO',
        '17' => 'TI',
        '18' => 'VI',
        '19' => 'VE',
        '20' => 'VR',
    );

    protected $_paymentCriptoTypes = array(
        '1'  => 'BCH',
        '2'  => 'BNB',
        '3'  => 'BUSD',
        '4'  => 'BTC',
        '5'  => 'DASH',
        '6'  => 'DOGE',
        '7'  => 'ETH',
        '8'  => 'LTC',
        '9'  => 'NANO',
        '10' => 'USDC',
        '11' => 'USDT',
    );

    public function __construct ()
    {
        // parent::__construct ();

        $this->_phone = preg_replace ('[\D]', null, Mage::getStoreConfig ('general/store_information/phone'));

        $this->_productComment = Mage::getStoreConfigFlag ('bot/product/comment');
        $this->_orderReview = Mage::getStoreConfigFlag ('bot/checkout/order_review');
    }

    protected function _saveMessage ($text, $chat, $type = Toluca_Bot_Helper_Data::MESSAGE_TYPE_ANSWER)
    {
        $message = Mage::getModel ('bot/message')
            ->setChatId ($chat->getId ())
            ->setBotType ($chat->getTypeId ())
            ->setTypeId ($type)
            ->setRemoteIp ($chat->getRemoteIp ())
            ->setEmail ($chat->getEmail ())
            ->setNumber ($chat->getNumber ())
            ->setPhone ($chat->getPhone ())
            ->setFirstname ($chat->getFirstname ())
            ->setLastname ($chat->getLastname ())
            ->setMessage ($text)
            ->setCreatedAt (date ('c'))
            ->save ()
        ;

        return $message;
    }

    protected function _getQuote ($botType, $from, $to, $senderName, $senderMessage)
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

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => '1'))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('customer_cellphone', array ('eq' => $from))
            ->addFieldToFilter ('customer_group_id',  array ('eq' => '0'))
            ->addFieldToFilter ('customer_is_guest',  array ('eq' => '1'))
            ->addFieldToFilter (Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_IS_BOT, array ('eq' => '1'))
            ->addFieldToFilter (Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_BOT_TYPE, array ('eq' => $botType))
        ;

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        $quote = $collection->getFirstItem ();

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
        }

        return $quote;
    }

    protected function _getCategoryCollection ($storeId)
    {
        $websiteId = Mage::app ()->getStore ($storeId)->getWebsite ()->getId ();

        $collection = Mage::getModel ('catalog/category')->getCollection ()
            ->addIsActiveFilter ()
            ->addNameToResult ()
            ->addFieldToFilter ('level', array ('gteq' => '2'))
        ;

        $collection->getSelect ()
            ->where ('main_table.is_active = 1')
            ->group ('main_table.entity_id')
            ->order ('main_table.position')
            ->join(
                array ('ccp' => Mage::getSingleton ('core/resource')->getTableName ('catalog_category_product')),
                'main_table.entity_id = ccp.category_id',
                array(
                    'products_count' => 'COUNT(ccp.product_id)',
                )
            )
            ->join(
                array ('cpf' => Mage::getSingleton ('core/resource')->getTableName ('catalog_product_flat_' . $storeId)),
                'ccp.product_id = cpf.entity_id',
                array ()
            )
            ->where ('cpf.status = ?',     Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->where ('cpf.visibility = ?', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->join(
                array ('ciss' => Mage::getSingleton ('core/resource')->getTableName ('cataloginventory_stock_status')),
                'cpf.entity_id = ciss.product_id AND ciss.stock_status = 1',
                array ()
            )
            ->where ('ciss.website_id = ?', $websiteId)
        ;

        return $collection;
    }

    protected function _getCategoryList ($storeId)
    {
        $result = null;

        $collection = $this->_getCategoryCollection ($storeId);

        foreach ($collection as $category)
        {
            $strLen = self::CATEGORY_ID_LENGTH - strlen ($category->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $result .= sprintf ('*%s*%s%s', $category->getPosition (), $strPad, $category->getName ()) . PHP_EOL;
        }

        return $result;
    }

    protected function _getProductCollection ($storeId, $categoryId)
    {
        $websiteId = Mage::app ()->getStore ($storeId)->getWebsite ()->getId ();

        $category = Mage::getModel ('catalog/category')->load ($categoryId);

        $collection = Mage::getModel ('catalog/product')->getCollection ()
            ->addAttributeToSelect ('name')
            ->addAttributeToSelect ('price')
            ->addAttributeToSelect ('special_price')
            ->addAttributeToSelect ('special_from_date')
            ->addAttributeToSelect ('special_to_date')
            ->addAttributeToSelect ('sku_position')
            ->addAttributeToFilter ('type_id', array ('in' => array (
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
            )))
            ->addCategoryFilter ($category)
            ->setVisibility (Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->addFinalPrice ()
        ;

        $collection->getSelect ()
            ->join(
                array ('ciss' => Mage::getSingleton ('core/resource')->getTableName ('cataloginventory_stock_status')),
                'e.entity_id = ciss.product_id AND ciss.stock_status = 1',
                array ()
            )
            ->where ('ciss.website_id = ?', $websiteId)
        ;

        return $collection;
    }

    protected function _getProductList ($storeId, $categoryId)
    {
        $result = null;

        $collection = $this->_getProductCollection ($storeId, $categoryId);

        foreach ($collection as $product)
        {
            $strLen = self::PRODUCT_ID_LENGTH - strlen ($product->getSkuPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            if (!floatval ($product->getFinalPrice ()))
            {
                $product->setData ('final_price', $product->getData ('price'));
            }

            $productPrice = Mage::helper ('core')->currency ($product->getFinalPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $product->getSkuPosition (), $strPad, $product->getName (), $productPrice) . PHP_EOL;
        }

        return $result;
    }

    protected function _getBundleOptionsCollection ($productId)
    {
        $collection = Mage::getModel ('bundle/option')->getCollection ()
            ->setProductIdFilter ($productId)
            ->joinValues (Mage_Core_Model_App::ADMIN_STORE_ID)
            ->setPositionOrder ()
        ;

        return $collection;
    }

    protected function _getBundleOptions ($productId, $selections = false)
    {
        $result = null;

        $collection = $this->_getBundleOptionsCollection ($productId);

        foreach ($collection as $option)
        {
            $strLen = self::OPTION_ID_LENGTH - strlen ($option->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $required = $option->getRequired () ? sprintf (' *(%s)* ', Mage::helper ('bot')->__('required')) : null;

            $result .= sprintf ('*%s*%s%s%s', $option->getPosition (), $strPad, $option->getDefaultTitle (), $required) . PHP_EOL;

            if ($selections)
            {
                $result .= PHP_EOL . $this->_getBundleSelections ($option) . PHP_EOL;
            }
        }

        return $result;
    }

    protected function _getBundleSelectionsCollection ($option)
    {
        $collection = Mage::getModel ('bundle/selection')->getCollection ()
            ->addAttributeToFilter ('name', array ('notnull' => true))
            ->addAttributeToSelect ('price')
            ->setOptionIdsFilter ($option->getId ())
            ->setPositionOrder ()
        ;

        return $collection;
    }

    protected function _getBundleSelections ($option)
    {
        $result = null;

        $collection = $this->_getBundleSelectionsCollection ($option);

        foreach ($collection as $selection)
        {
            $strLen = self::VALUE_ID_LENGTH - strlen ($selection->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $selectionPrice = Mage::helper ('core')->currency ($selection->getFinalPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $selection->getPosition (), $strPad, $selection->getName (), $selectionPrice) . PHP_EOL;
        }

        return $result;
    }

    protected function _getProductOptions ($productId, $values = false)
    {
        $result = null;

        $product = Mage::getModel ('catalog/product')->load ($productId);

        foreach ($product->getOptions () as $option)
        {
            $strLen = self::OPTION_ID_LENGTH - strlen ($option->getSortOrder ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $require = $option->getIsRequire () ? sprintf (' *(%s)* ', Mage::helper ('bot')->__('required')) : null;

            $result .= sprintf ('*%s*%s%s%s', $option->getSortOrder (), $strPad, $option->getTitle (), $require) . PHP_EOL;

            if ($values)
            {
                $result .= PHP_EOL . $this->_getProductValues ($option) . PHP_EOL;
            }
        }

        return $result;
    }

    protected function _getProductValues ($option)
    {
        $result = null;

        foreach ($option->getValues () as $value)
        {
            $strLen = self::VALUE_ID_LENGTH - strlen ($value->getSortOrder ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $valuePrice = Mage::helper ('core')->currency ($value->getPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $value->getSortOrder (), $strPad, $value->getTitle (), $valuePrice) . PHP_EOL;
        }

        return $result;
    }

    protected function _getCartReview ($quoteId, $storeId)
    {
        $result = Mage::helper ('bot/message')->getThisIsYourShoppingCartText () . PHP_EOL . PHP_EOL;

        $quote = Mage::getModel ('sales/quote')->load ($quoteId);

        foreach ($quote->getAllVisibleItems () as $item)
        {
            $strLen = self::QUANTITY_LENGTH - strlen ($item ['qty'] . 'x');
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $itemRowTotal = Mage::helper ('core')->currency ($item ['row_total'], true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $item ['qty'] . 'x', $strPad, $item ['name'], $itemRowTotal) . PHP_EOL . PHP_EOL;

            $itemBundleOption = $item->getBuyRequest ()->getData ('bundle_option');

            foreach ($itemBundleOption as $itemBundleOptionId => $itemBundleOptionValues)
            {
                if (strcmp ($item->getProduct ()->getTypeId (), Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) != 0)
                {
                    break;
                }

                $itemBundleOptionCollection = $item->getProduct ()->getTypeInstance (true)->getOptionsCollection ($item->getProduct ());

                foreach ($itemBundleOptionCollection as $option)
                {
                    if ($option->getId () == $itemBundleOptionId)
                    {
                        $result .= sprintf ('*%s*: ', $option->getDefaultTitle ());

                        $itemBundleSelectionsTitles = array ();

                        $itemBundleSelectionsCollection = $item->getProduct ()->getTypeInstance (true)->getSelectionsCollection (array ($option->getId ()), $item->getProduct ());

                        foreach ($itemBundleSelectionsCollection as $selection)
                        {
                            if (in_array ($selection->getSelectionId (), $itemBundleOptionValues))
                            {
                                $selectionPrice = Mage::helper ('core')->currency ($selection->getPrice (), true, false);

                                $itemBundleSelectionsTitles [] = sprintf ('%s *%s*', $selection->getName (), $selectionPrice);
                            }
                        }

                        $result .= implode (', ', $itemBundleSelectionsTitles) . PHP_EOL . PHP_EOL;
                    }
                }
            }

            $itemOptions = $item->getBuyRequest ()->getData ('options');

            foreach ($itemOptions as $itemOptionId => $itemOptionValues)
            {
                foreach ($item->getProduct ()->getOptions () as $option)
                {
                    if ($option->getOptionId () == $itemOptionId)
                    {
                        $result .= sprintf ('*%s*: ', $option->getDefaultTitle ());

                        $itemOptionValuesTitles = array ();

                        foreach ($option->getValues() as $value)
                        {
                            if (in_array ($value->getOptionTypeId (), $itemOptionValues))
                            {
                                $itemOptionValuesTitles [] = $value->getDefaultTitle ();
                            }
                        }

                        $result .= implode (', ', $itemOptionValuesTitles) . PHP_EOL . PHP_EOL;
                    }
                }
            }

            $itemAdditionalOptions = $item->getBuyRequest ()->getData ('additional_options');

            foreach ($itemAdditionalOptions as $additionalOption)
            {
                $result .= sprintf ('*%s*: %s', $additionalOption ['label'], $additionalOption ['value']) . PHP_EOL . PHP_EOL;
            }
        }

        return $result;
    }

    protected function _getCheckoutReview ($quoteId, $storeId)
    {
        $result = $this->_getCartReview ($quoteId, $storeId);

        $info = Mage::getModel ('checkout/cart_api')->info ($quoteId, $storeId);

        $result .= sprintf ('*%s*: %s', Mage::helper ('bot')->__('Address'), implode (' ', explode ("\n", $info ['shipping_address']['street'])))
            . PHP_EOL . PHP_EOL
        ;

        $shippingDescription = $info ['shipping_address']['shipping_description'];
        $shippingAmount      = Mage::helper ('core')->currency ($info ['shipping_address']['shipping_amount'], true, false);

        $result .= sprintf ('*%s*: %s *%s*', Mage::helper ('bot')->__('Shipping'), $shippingDescription, $shippingAmount)
            . PHP_EOL . PHP_EOL
        ;

        $paymentMethod = $info ['payment']['method'];
        $paymentTitle  = Mage::getStoreconfig ("payment/{$paymentMethod}/title", $storeId);

        $grandTotal = Mage::helper ('core')->currency ($info ['shipping_address']['grand_total'], true, false);

        $result .= sprintf ('*%s*: %s  *%s*', Mage::helper ('bot')->__('Payment'), $paymentTitle, $grandTotal)
            . PHP_EOL . PHP_EOL
        ;

        switch ($paymentMethod)
        {
            case 'cashondelivery':
            {
                $paymentChange = $info ['payment']['additional_information']['change_type'];
                $paymentCash   = $info ['payment']['additional_information']['cash_amount'];

                $result .= Mage::helper ('bot/message')->getNeedChangeForMoneyText ($paymentChange, $paymentCash) . PHP_EOL . PHP_EOL;

                break;
            }
            case 'machineondelivery':
            {
                $paymentCcType = $info ['payment']['cc_type'];

                $result .= Mage::helper ('bot/message')->getCardTypeForMachineText ($paymentCcType) . PHP_EOL . PHP_EOL;

                break;
            }
            case 'gamuza_pagcripto_payment':
            {
                $paymentCcType = $info ['payment']['cc_type'];

                $result .= Mage::helper ('bot/message')->getCurrencyTypeForCriptoText ($paymentCcType) . PHP_EOL . PHP_EOL;

                break;
            }
        }

        if ($this->_orderReview)
        {
            $result .= Mage::helper ('bot/message')->getEnterToConfirmOrderText ();
        }

        return $result;
    }

    protected function _getAllowedShipping ($shippingMethods, $shippingId)
    {
        foreach ($shippingMethods as $method)
        {
            if (!strcmp ($method ['code'], $this->_shippingMethods [$shippingId]))
            {
                return true;
            }
        }

        return false;
    }

    protected function _getAllowedPayment ($paymentMethods, $paymentId)
    {
        foreach ($paymentMethods as $method)
        {
            if (!strcmp ($method ['code'], $this->_paymentMethods [$paymentId]))
            {
                return true;
            }
        }

        return false;
    }

    protected function _getAllowedCcType ($paymentMethods, $paymentId)
    {
        foreach ($paymentMethods as $method)
        {
            if (!strcmp ($method ['code'], 'machineondelivery'))
            {
                foreach ($method ['cc_types'] as $id => $cctype)
                {
                    if (!strcmp ($id, $this->_paymentCcTypes [$paymentId]))
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function _getAllowedCriptoType ($paymentMethods, $paymentId)
    {
        foreach ($paymentMethods as $method)
        {
            if (!strcmp ($method ['code'], 'gamuza_pagcripto_payment'))
            {
                foreach ($method ['cc_types'] as $id => $cctype)
                {
                    if (!strcmp ($id, $this->_paymentCriptoTypes [$paymentId]))
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function _getCardList ($quoteId, $storeId)
    {
        $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($quoteId, $storeId);

        foreach ($paymentMethods as $paymentId => $paymentValue)
        {
            if (!strcmp ($paymentValue ['code'], 'machineondelivery'))
            {
                foreach ($paymentValue ['cc_types'] as $id => $cctype)
                {
                    if (!in_array ($id, $this->_paymentCcTypes))
                    {
                        unset ($paymentValue ['cc_types'][$id]);
                    }
                }

                $result = Mage::helper ('bot/message')->getChooseTypeOfCardText () . PHP_EOL . PHP_EOL;

                foreach ($this->_paymentCcTypes as $id => $cctype)
                {
                    foreach ($paymentValue ['cc_types'] as $_id => $_cctype)
                    {
                        if (!strcmp ($cctype, $_id))
                        {
                            $strLen = self::CCTYPE_ID_LENGTH - strlen ($id);
                            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                            $result .= sprintf ("*%s*%s%s", $id, $strPad, $_cctype) . PHP_EOL;
                        }
                    }
                }

                return $result;
            }
        }
    }

    protected function _getCriptoList ($quoteId, $storeId)
    {
        $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($quoteId, $storeId);

        foreach ($paymentMethods as $paymentId => $paymentValue)
        {
            if (!strcmp ($paymentValue ['code'], 'gamuza_pagcripto_payment'))
            {
                foreach ($paymentValue ['cc_types'] as $id => $cctype)
                {
                    if (!in_array ($id, $this->_paymentCriptoTypes))
                    {
                        unset ($paymentValue ['cc_types'][$id]);
                    }
                }

                $result = Mage::helper ('bot/message')->getChooseTypeOfCriptoText () . PHP_EOL . PHP_EOL;

                foreach ($this->_paymentCriptoTypes as $id => $cctype)
                {
                    foreach ($paymentValue ['cc_types'] as $_id => $_cctype)
                    {
                        if (!strcmp ($cctype, $_id))
                        {
                            $strLen = self::CCTYPE_ID_LENGTH - strlen ($id);
                            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                            $result .= sprintf ("*%s*%s%s", $id, $strPad, $_cctype) . PHP_EOL;
                        }
                    }
                }

                return $result;
            }
        }
    }
}

