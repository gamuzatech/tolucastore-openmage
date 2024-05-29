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
        $schemesDirectory = Mage::getBaseDir ('lib') . DS . 'Gamuza' . DS . 'Brazil' . DS . 'Schemes';

        $info = array_replace_recursive ($info, array(
            'config' => array(
                'brazil' => array(
                    'schemes' => $schemesDirectory,
                    'directory' => $brazilDirectory,
                    'certificate' => array(
                        'type_id'   => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_TYPE)),
                        'directory' => $certificateDirectory,
                        'filename'  => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_FILENAME),
                        'password'  => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CERTIFICATE_PASSWORD),
                    ),
                    'csc' => array(
                        'id'   => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CSC_ID),
                        'code' => Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_CSC_CODE),
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
                    'nfe' => array(
                        'print_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_PRINT_ID)),
                        'batch_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_BATCH_ID)),
                        'model_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_MODEL_ID)),
                        'series_id' => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFE_SERIES_ID)),
                    ),
                    'nfce' => array(
                        'print_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_PRINT_ID)),
                        'batch_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_BATCH_ID)),
                        'model_id'  => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_MODEL_ID)),
                        'series_id' => intval (Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_SERIES_ID)),
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

    public function salesOrderPlaceAfter (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder ();

        if (in_array ($order->getPayment ()->getMethod (), array (
            Gamuza_Brazil_Model_Payment_Method_Pix::CODE,
        )))
        {
            $order->setData (Gamuza_Brazil_Helper_Data::ORDER_ATTRIBUTE_IS_PIX, true)->save ();
        }
    }
}

