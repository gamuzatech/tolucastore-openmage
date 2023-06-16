<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DESKTOP_ADMIN_USER  = 'tolucastoredesktop';
    const DESKTOP_ADMIN_NAME  = 'Toluca Store Desktop';
    const DESKTOP_ADMIN_EMAIL = 'desktop@toluca.com.br';

    const DESKTOP_API_USER  = 'tolucastoredesktop';
    const DESKTOP_API_NAME  = 'Toluca Store Desktop';
    const DESKTOP_API_EMAIL = 'desktop@toluca.com.br';

    const BASIC_ORDER_SERVICE_TABLE = 'basic_order_service';

    const CATEGORY_ATTRIBUTE_INCLUDE_IN_HOME = 'include_in_home';
    const CATEGORY_ATTRIBUTE_CODE = 'code';
    const CATEGORY_ATTRIBUTE_SKU = 'sku';

    const CUSTOMER_ATTRIBUTE_CODE = 'code';
    const CUSTOMER_ATTRIBUTE_CELLPHONE = 'cellphone';
    const CUSTOMER_ATTRIBUTE_SECONDARY_NAME = 'secondary_name';

    const CUSTOMER_ADDRESS_ATTRIBUTE_CELLPHONE = 'cellphone';

    const CUSTOMER_GROUP_ATTRIBUTE_NAME = 'name';
    const CUSTOMER_GROUP_ATTRIBUTE_IS_SYSTEM = 'is_system';

    const CUSTOMER_GROUP_NOT_LOGGED_IN = 0;
    const CUSTOMER_GROUP_GENERAL = 1;
    const CUSTOMER_GROUP_WHOLESALE = 2;
    const CUSTOMER_GROUP_RETAILER = 3;
    const CUSTOMER_GROUP_SUPPLIER = 4;
    const CUSTOMER_GROUP_MANUFACTURER = 5;
    const CUSTOMER_GROUP_DISTRIBUTER = 6;
    const CUSTOMER_GROUP_SELLER = 7;
    const CUSTOMER_GROUP_RESELLER = 8;

    const CUSTOMER_GENDER_MALE   = 1;
    const CUSTOMER_GENDER_FEMALE = 2;

    const ORDER_ATTRIBUTE_IS_APP = 'is_app';
    const ORDER_ATTRIBUTE_IS_BOT = 'is_bot';
    const ORDER_ATTRIBUTE_IS_PDV = 'is_pdv';
    const ORDER_ATTRIBUTE_IS_SERVICE = 'is_service';

    const ORDER_ATTRIBUTE_CUSTOMER_CELLPHONE = 'customer_cellphone';

    const ORDER_SUFFIX_APP    = 'APP';
    const ORDER_SUFFIX_BOT    = 'BOT';
    const ORDER_SUFFIX_PDV    = 'PDV';
    const ORDER_SUFFIX_ADMIN  = 'ADMIN';
    const ORDER_SUFFIX_STORE  = 'STORE';
    const ORDER_SUFFIX_OTHER  = 'OTHER';

    const PRODUCT_ATTRIBUTE_BRAND = 'brand';
    const PRODUCT_ATTRIBUTE_CODE = 'code';
    const PRODUCT_ATTRIBUTE_COLOR = 'color';
    const PRODUCT_ATTRIBUTE_MANUFACTURER = 'manufacturer';
    const PRODUCT_ATTRIBUTE_SIZE = 'size';
    const PRODUCT_ATTRIBUTE_EAN = 'ean';
    const PRODUCT_ATTRIBUTE_FREE_SHIPPING = 'free_shipping';
    const PRODUCT_ATTRIBUTE_PRICE_TYPE   = 'price_type';
    const PRODUCT_ATTRIBUTE_SKU_POSITION = 'sku_position';

    const PRODUCT_ATTRIBUTE_CUSTOMER_OF_SUPPLIER = 'customer_of_supplier';
    const PRODUCT_ATTRIBUTE_CUSTOMER_OF_MANUFACTURER = 'customer_of_manufacturer';
    const PRODUCT_ATTRIBUTE_CUSTOMER_OF_DISTRIBUTER = 'customer_of_distributer';
    const PRODUCT_ATTRIBUTE_CUSTOMER_OF_SELLER = 'customer_of_seller';
    const PRODUCT_ATTRIBUTE_CUSTOMER_OF_RESELLER = 'customer_of_reseller';

    const PRODUCT_PRICE_VIEW_PRICE_RANGE    = 0;
    const PRODUCT_PRICE_VIEW_AS_LOW_AS      = 1;
    const PRODUCT_PRICE_VIEW_AS_HIGH_AS     = 2;
    const PRODUCT_PRICE_VIEW_AS_LOW_AS_ONE  = 3;
    const PRODUCT_PRICE_VIEW_AS_HIGH_AS_ONE = 4;
    const PRODUCT_PRICE_VIEW_PRICE_STATIC   = 5;
    const PRODUCT_PRICE_VIEW_PRICE_AVERAGE  = 6;

    const ORDER_STATUS_PREPARING = 'preparing';
    const ORDER_STATUS_PAID      = 'paid';
    const ORDER_STATUS_SHIPPED   = 'shipped';
    const ORDER_STATUS_DELIVERED = 'delivered';
    const ORDER_STATUS_REFUNDED  = 'refunded';

    const SQL_PT_BR = 'pt_BR' . DS . 'sql' . DS . 'sql_pt_br_19_utf8.txt';

    const XML_PATH_GENERAL_STORE_INFORMATION_LOGO = 'general/store_information/logo';

    public function getLocaleCode ($scope = 'default', $scope_id = 0)
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        return $read->fetchOne(
            " SELECT value FROM core_config_data " .
            " WHERE scope = '$scope' AND scope_id = $scope_id " .
            " AND path = 'general/locale/code'"
        );
    }

    public function getLogoUrl ()
    {
        $media = Mage::getBaseDir('media') . DS . 'store' . DS . 'info';
        $file  = Mage::getStoreConfig(self::XML_PATH_GENERAL_STORE_INFORMATION_LOGO);

        if (is_file($media . DS . $file))
        {
            return Mage::getBaseUrl('media') . 'store/info/' . $file;
        }
    }

    public function getTotals (Mage_Adminhtml_Block_Widget_Grid $grid)
    {
        $fieldsTotals = $grid->_fieldsTotals;

        foreach ($grid->getCollection () as $item)
        {
            foreach ($fieldsTotals as $id => $value)
            {
                $fieldsTotals [$id] += floatval ($item->getData ($id));
            }
        }

        $object = new Varien_Object ();
        $object->addData ($fieldsTotals);

        return $object;
    }

    public function isMobile ()
    {
        /*
        $result = Zend_Http_UserAgent_Mobile::match(
            Mage::helper ('core/http')->getHttpUserAgent (),
            $_SERVER
        );

        return $result;
        */
        return preg_match ('/(iPad|iPhone|Darwin|Android|Dalvik)/i', $_SERVER ['HTTP_USER_AGENT']);
    }
}

