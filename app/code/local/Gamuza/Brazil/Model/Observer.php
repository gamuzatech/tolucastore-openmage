<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Observer
{
    public function basicMagentoApiInfo (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $info = $event->getInfo ();

        $brazilDirectory = Mage::getBaseDir ('var') . DS . 'brazil';
        $certificateDirectory = $brazilDirectory . DS . 'cert';
        $reportsDirectory = Mage::getBaseDir ('lib') . DS . 'Gamuza' . DS . 'Brazil' . DS . 'Reports';
        $schemesDirectory = Mage::getBaseDir ('lib') . DS . 'Gamuza' . DS . 'Brazil' . DS . 'Schemes';

        $info = array_replace_recursive ($info, array(
            'config' => array(
                'brazil' => array(
                    'reports' => $reportsDirectory,
                    'schemes' => $schemesDirectory,
                    'directory' => $brazilDirectory,
                    'certificate' => array(
                        'type_id'   => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_TYPE_ID)),
                        'directory' => $certificateDirectory,
                        'filename'  => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_FILENAME),
                        'password'  => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_PASSWORD),
                    ),
                    'setting' => array(
                        'active'         => Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_ACTIVE),
                        'environment_id' => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_ENVIRONMENT_ID)),
                        'version'        => strval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_VERSION)),
                        'timeout'        => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_TIMEOUT)),
                        'country_id'     => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_COUNTRY_ID)),
                        'region_id'      => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_REGION_ID)),
                        'city_id'        => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_CITY_ID)),
                        'crt_id'         => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_CRT_ID)),
                        'company_ie'     => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_COMPANY_IE),
                        'company_im'     => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_COMPANY_IM),
                        'remove_accents' => Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_REMOVE_ACCENTS),
                    ),
                    'csc' => array(
                        'id'   => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CSC_ID),
                        'code' => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CSC_CODE),
                    ),
                    'nfce' => array(
                        'print_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_PRINT_ID)),
                        'batch_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_BATCH_ID)),
                        'model_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_MODEL_ID)),
                        'series_id' => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_SERIES_ID)),
                    ),
                    'nfe' => array(
                        'print_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_PRINT_ID)),
                        'batch_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_BATCH_ID)),
                        'model_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_MODEL_ID)),
                        'series_id' => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_SERIES_ID)),
                    ),
                    'cashier' => array(
                        'show_history_nfes'  => Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CASHIER_SHOW_HISTORY_NFES),
                        'show_history_nfces' => Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CASHIER_SHOW_HISTORY_NFCES),
                    ),
                ),
                'payment' => array(
                    'gamuza_brazil_pix' => array(
                        'key' => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_PAYMENT_GAMUZA_BRAZIL_PIX_KEY),
                    ),
                ),
            ),
        ));

        $event->setInfo ($info);
    }

    public function salesQuoteItemSetProduct (Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem ();
        $product   = $observer->getProduct ();

        $productBrazilNcm  = $product->getData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_NCM);
        $productBrazilCest = $product->getData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CEST);
        $productBrazilCfop = $product->getData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CFOP);

        $quoteItem->setData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_NCM,  $productBrazilNcm);
        $quoteItem->setData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CEST, $productBrazilCest);
        $quoteItem->setData (Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CFOP, $productBrazilCfop);
    }

    public function salesQuoteSaveBefore (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $quote = $event->getQuote ();
        $customer = $quote->getCustomer ();

        $customerBrazilRgIe   = $customer->getData (Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_RG_IE);
        $customerBrazilIeIcms = $customer->getData (Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_IE_ICMS);

        $quote->setData (Gamuza_Brazil_Helper_Data::ORDER_ATTRIBUTE_BRAZIL_RG_IE,   $customerBrazilRgIe);
        $quote->setData (Gamuza_Brazil_Helper_Data::ORDER_ATTRIBUTE_BRAZIL_IE_ICMS, $customerBrazilIeIcms);
    }

    public function salesOrderPlaceAfter (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder ();

        if (in_array ($order->getPayment ()->getMethod (), array (
            Gamuza_Brazil_Model_Payment_Method_Pix::CODE,
        )))
        {
            $order->setData (Gamuza_Brazil_Helper_Data::ORDER_ATTRIBUTE_IS_PIX, true)->save ();

            $order->getPayment ()
                ->setData (
                    Gamuza_Brazil_Helper_Data::ORDER_PAYMENT_ATTRIBUTE_BRAZIL_PIX_KEY,
                    Mage::helper ('brazil/pix')->_getKey ($order)
                )
                ->save ()
            ;
        }
    }
}

