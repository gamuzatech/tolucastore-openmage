<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Basic module observer
 */
class Gamuza_Basic_Model_Observer
{
    const PRINTING_VALUE_NO = Gamuza_Basic_Model_Eav_Entity_Attribute_Source_Product_Printing::VALUE_NO;

    const SALES_QUOTE_LIFETIME = 86400;

    public function adminhtmlCmsPageEditTabContentPrepareForm ($observer)
    {
        $event = $observer->getEvent ();
        $form  = $event->getForm ();

        $form->getElement ('content')->setRequired (false);
    }

    public function adminhtmlControllerActionPredispatchStart ($observer)
    {
        Mage::getDesign ()->setArea ('adminhtml')->setTheme ('zzz'); // use fallback theme
    }

    public function basicMagentoApiInfo (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $info = $event->getInfo ();

        $info = array_replace_recursive ($info, array(
            'config' => array(
                'general' => array(
                    'store_information' => array(
                        'taxvat'  => Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_TAXVAT),
                        'company' => Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_COMPANY),
                        'name'    => Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_NAME),
                        'phone'   => Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_GENERAL_STORE_INFORMATION_PHONE),
                    ),
                ),
                'shipping' => array(
                    'origin' => Mage::getModel ('basic/shipping_api')->origin (Mage::app ()->getStore ()->getId ()),
                ),
            ),
        ));

        $event->setInfo ($info);
    }

    public function catalogProductSaveBefore ($observer)
    {
        $event   = $observer->getEvent ();
        $product = $event->getProduct ();

        {
            $prefix = time ();
            $token  = hash ('sha512', uniqid (rand (), true));

            $product->setSku ($prefix . '_' . $token);
            $product->setUrlKey ($prefix . '-' . $token);
        }

        return $this;
    }

    public function catalogCategorySaveAfter ($observer)
    {
        $event    = $observer->getEvent ();
        $category = $event->getCategory ();

        {
            $resource = Mage::getSingleton ('core/resource');
            $write    = $resource->getConnection ('core_write');

            /**
             * SKU
             */
            $table = $resource->getTableName ('catalog/category');

            $token = hash ('crc32b', $category->getId ());

            $query = sprintf ("UPDATE {$table} SET sku = '{$token}' WHERE entity_id = %s LIMIT 1",
                $category->getId ()
            );

            $write->query ($query);
        }

        return $this;
    }

    public function catalogProductSaveAfter ($observer)
    {
        $event   = $observer->getEvent ();
        $product = $event->getProduct ();

        {
            $resource = Mage::getSingleton ('core/resource');
            $write    = $resource->getConnection ('core_write');

            /**
             * SKU
             */
            $table = $resource->getTableName ('catalog/product');

            $token = hash ('crc32b', $product->getId ());

            $query = sprintf ("UPDATE {$table} SET sku = '{$token}' WHERE entity_id = %s LIMIT 1",
                $product->getId ()
            );

            $write->query ($query);

            /**
             * URL Key
             */
            $attribute = Mage::getSingleton ('eav/config')->getAttribute (Mage_Catalog_Model_Product::ENTITY , 'url_key');

            $table = $resource->getTableName ('catalog_product_entity_' . $attribute->getBackendType ());

            $query = sprintf ("UPDATE {$table} SET value = '{$token}' WHERE attribute_id = %s AND entity_id = %s",
                $attribute->getId (), $product->getId ()
            );

            $write->query ($query);
        }

        $mediaDir = Mage::getBaseDir (Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $mediaGallery = $product->getMediaGallery ();

        if (empty ($mediaGallery ['images']))
        {
            return $this;
        }

        foreach ($mediaGallery ['images'] as $image)
        {
            $file = $mediaDir . DS . 'catalog' . DS . 'product' . DS . $image ['file'];

            if (!file_exists ($file)) continue;

            $image = new Varien_Image ($file);

            $image->backgroundColor (array (255, 255, 255));
            $image->save ($file);

            if ($image->getOriginalWidth () > 1024)
            {
                $original = imagecreatefromstring (file_get_contents ($file));

                $resized = imagecreatetruecolor (1024, 1024);

                imagecopyresampled ($resized, $original, 0, 0, 0, 0, 1024, 1024, $image->getOriginalWidth (), $image->getOriginalHeight ());

                imagejpeg ($resized, $file, 75);
            }
        }

        return $this;
    }

    public function checkoutCartProductAddBefore ($observer)
    {
        $event = $observer->getEvent ();
        $request = $event->getRequest ();
        $product = $event->getProduct ();

        /**
         * Options MaxLength
         */
        $options = $request->getData ('options');

        if (is_array ($options) && count ($options) > 0)
        {
            foreach ($options as $id => $value)
            {
                if (!is_array ($value)) continue;

                $valueCount = count ($value);

                $productOptions = $product->getOptions ();

                if ($valueCount > 0 && count ($productOptions) > 0)
                {
                    foreach ($productOptions as $option)
                    {
                        if ($option->getOptionId () == $id)
                        {
                            $optionMaxLength = intval ($option->getMaxLength ());

                            if ($optionMaxLength > 0 && $valueCount > $optionMaxLength)
                            {
                                $message = Mage::helper ('checkout')->__("You can select up to %s options in '%s'", $option->getMaxLength (), $option->getTitle ());

                                throw new Mage_Core_Exception ($message);
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkoutCartProductAddAfter($observer)
    {
        $event = $observer->getEvent ();
        $quoteItem = $event->getQuoteItem ();

        $additionalOptions = $quoteItem->getOptionByCode('additional_options');

        if (!empty ($additionalOptions))
        {
            return $this;
        }

        $infoByRequest = $quoteItem->getOptionByCode('info_buyRequest');

        if (empty ($infoByRequest))
        {
            return $this;
        }

        $value = unserialize ($infoByRequest->getValue ());

        if (is_array ($value) && !empty ($value ['additional_options']))
        {
            $additionalOptions = $value ['additional_options'];

            if (is_array ($additionalOptions) && count ($additionalOptions)
                && !empty ($additionalOptions [0]['code'])
                && !empty ($additionalOptions [0]['label'])
                && !empty ($additionalOptions [0]['value']))
            {
                $quoteItem->addOption (array (
                    'code' => 'additional_options',
                    'product_id' => $quoteItem->getProductId (),
                    'value' => serialize (array ($additionalOptions [0]))
                ));
            }
        }
    }

    public function warmer()
    {
        $stores = Mage::app()->getStores();

        foreach ($stores as $store)
        {
            try
            {
                $storeId = $store->getId();

                $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $baseUrl);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

                $result = curl_exec($ch);

                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                if ($httpCode != 200)
                {
                    throw new Exception(sprintf('MISS: %s', $baseUrl));
                }

                Mage::log(sprintf('HIT: %s', $baseUrl), null, 'gamuza_basic_warmer.log');
            }
            catch (Exception $e)
            {
                Mage::log($e->getMessage(), null, 'gamuza_basic_warmer.log');
            }
        }
    }

    public function cleanExpiredQuotes()
    {
        Mage::getModel('sales/observer')->cleanExpiredQuotes(null);

        /** @var $quotes Mage_Sales_Model_Mysql4_Quote_Collection */
        $quotes = Mage::getModel('sales/quote')->getCollection()
            ->addFieldToFilter('items_count', array ('gt' => 0))
        ;

        foreach($quotes as $quote)
        {
            $collectTotals = false;

            foreach($quote->getAllItems() as $item)
            {
                if (!strcmp($item->getProductType(), Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) && $item->getHasError())
                {
                    $item->delete();

                    $collectTotals = true;
                }
            }

            if ($collectTotals)
            {
                $quote->collectTotals();
            }

            $quote->save ();
        }

        /** @var $quotes Mage_Sales_Model_Mysql4_Quote_Collection */
        $quotes = Mage::getModel('sales/quote')->getCollection()
            ->addFieldToFilter('items_count', array ('eq' => 0))
        ;

        /*
        $quotes->addFieldToFilter('updated_at', array('to'=>date("Y-m-d H:i:s", mktime(23, 59, 59) - self::SALES_QUOTE_LIFETIME)));
        */

        $quotes->walk('delete');

        return $this;
    }

    public function cleanExpiredBackups()
    {
        $point = date ('c', strtotime ('-30 days'));

        foreach (Mage::getModel ('backup/fs_collection') as $fs)
        {
            $stamp = date ('c', $fs->getTime ());

            if ($stamp < $point)
            {
                $backup = Mage::getModel ('backup/backup')->loadByTimeAndType ($fs->getTime (), $fs->getType ());

                try
                {
                    $backup->deleteFile();
                }
                catch (Exception $e)
                {
                    Mage::logException ($e);
                }
            }
        }

        return $this;
    }

    public function controllerActionPredispatch ($observer)
    {
        $email = Mage::getStoreConfig ('system/cron/error_email');
        $jobs  = Mage::getStoreConfig ('system/cron/jobs');

        if (empty ($email) || empty ($jobs))
        {
            return $this;
        }

$content = <<< CONTENT
MAILTO="{$email}"
{$jobs}
CONTENT;

        $content = str_replace ("\r\n", PHP_EOL, $content); // dos2unix

        $crontab = shell_exec ('crontab -l');

        if (strcmp ($content, $crontab) != 0)
        {
            $filename = tempnam (sys_get_temp_dir (), 'crontab_');

            file_put_contents ($filename, $content);

            shell_exec (sprintf ('crontab %s', $filename));

            unlink ($filename);
        }

        return $this;
    }

    public function salesOrderPlaceAfter ($observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder();

        foreach ($order->getAllItems () as $item)
        {
            if ($item->getCustomWeight () > 0)
            {
                $order->setData (Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_WEIGHTED, true)->save ();

                break;
            }
        }

        $orderItems = Mage::getResourceModel ('sales/order_item_collection')
            ->setOrderFilter ($order)
            ->filterByTypes (array (Gamuza_Basic_Model_Catalog_Product_Type_Service::TYPE_SERVICE))
        ;

        if ($orderItems->getSize() > 0)
        {
            $basic = Mage::getModel ('basic/order_service')
                ->setOrder($order)
                ->setState (Gamuza_Basic_Model_Order_Service::STATE_OPEN)
                ->save()
            ;

            $order->setData (Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_SERVICE, true)->save ();
        }

        return $this;
    }

    public function orderCancelAfter ($observer)
    {
        $this->_updateOrderServiceState (
            $observer->getEvent ()->getOrder (),
            Gamuza_Basic_Model_Order_Service::STATE_CANCELED
        );

        return $this;
    }

    public function salesQuoteItemSetProduct (Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem ();
        $product   = $observer->getProduct ();

        $productGTIN = $product->getData (Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_GTIN);
        $productPrinting = $product->getData (Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_PRINTING);

        $quoteItem->setData (Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_GTIN, $productGTIN);

        $quoteItemIsPrinted = $quoteItem->getData (Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_IS_PRINTED);

        if (strcmp ($quoteItemIsPrinted, '1') != 0 && !strcmp ($productPrinting, self::PRINTING_VALUE_NO))
        {
            $quoteItem->setData (Gamuza_Basic_Helper_Data::ORDER_ITEM_ATTRIBUTE_IS_PRINTED, '1');
        }
    }

    public function salesQuoteCollectTotalsAfter ($observer)
    {
        $quote = $observer->getEvent ()->getQuote ();

        foreach ($quote->getAllItems () as $item)
        {
            if ($item->getCustomWeight () > 0)
            {
                $quote->setData (Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_WEIGHTED, true)->save ();

                break;
            }
        }

        $collection = Mage::getResourceModel ('sales/quote_item_collection')
            ->setQuote ($quote)
            ->addFieldToFilter ('product_type', Gamuza_Basic_Model_Catalog_Product_Type_Service::TYPE_SERVICE)
        ;

        if ($collection->getSize() > 0)
        {
            $quote->setData (Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_SERVICE, true)->save ();
        }

        return $this;
    }

    public function salesOrderPreparingAfter ($observer)
    {
        $this->_updateOrderServiceState (
            $observer->getEvent ()->getOrder (),
            Gamuza_Basic_Model_Order_Service::STATE_PROCESSING
        );

        return $this;
    }

    public function salesOrderDeliveredAfter ($observer)
    {
        $this->_updateOrderServiceState (
            $observer->getEvent ()->getOrder (),
            Gamuza_Basic_Model_Order_Service::STATE_CLOSED
        );

        return $this;
    }

    public function salesOrderInvoicePay ($observer)
    {
        $event   = $observer->getEvent ();
        $invoice = $event->getInvoice ();
        $order   = $invoice->getOrder ();

        foreach ($order->getAllItems () as $item)
        {
            $product = $item->getProduct ();

            foreach ($product->getMaterialProducts () as $productMaterial)
            {
                $productMaterialQty = $productMaterial->getQty () * $item->getQtyOrdered ();

                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productMaterial);

                if ($stockItem && $stockItem->getId () && $stockItem->getQty () > $productMaterialQty)
                {
                    $stockItem->subtractQty ($productMaterialQty)
                        ->setSaveMovementMessage (__('Product raw material ( %s ) %s', $product->getSku (), $product->getName ()))
                        ->save ()
                    ;
                }
            }
        }
    }

    public function salesOrderCreditmemoRefund ($observer)
    {
        $this->_updateOrderServiceState (
            $observer->getEvent ()->getCreditmemo ()->getOrder (),
            Gamuza_Basic_Model_Order_Service::STATE_REFUNDED
        );

        return $this;
    }

    private function _updateOrderServiceState ($order, $state)
    {
        if ($order->getData (Gamuza_Basic_Helper_Data::ORDER_ATTRIBUTE_IS_SERVICE))
        {
            $service = Mage::getModel ('basic/order_service')->load ($order->getId (), 'order_id');

            if ($service && $service->getId ())
            {
                $service->setState ($state)->save ();
            }
        }
    }
}

