<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$coreConfig = Mage::getModel ('core/config');

/**
 * General
 */
$coreConfig->saveConfig (Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY, 'BR');

$coreConfig->saveConfig ('general/country/allow', 'BR');

/**
 * Web
 */
$coreConfig->saveConfig (Mage_Core_Model_Cookie::XML_PATH_COOKIE_LIFETIME, '86400');
$coreConfig->saveConfig (Mage_Core_Model_Cookie::XML_PATH_COOKIE_PATH, '/');

$coreConfig->saveconfig (Mage_Core_Model_Session_Abstract::XML_PATH_USE_FRONTEND_SID, '0');
$coreConfig->saveConfig (Mage_Admin_Model_Session::XML_PATH_ALLOW_SID_FOR_ADMIN_AREA, '1');

/**
 * Design
 */
$coreConfig->saveConfig ('design/head/default_title',       'Toluca Store');
$coreConfig->saveConfig ('design/head/default_description', null);
$coreConfig->saveConfig ('design/head/default_keywords',    null);

$coreConfig->saveConfig ('design/header/logo_src',       'images/logo.png');
$coreConfig->saveConfig ('design/header/logo_src_small', 'images/logo.png');
$coreConfig->saveConfig ('design/header/logo_alt',       'Toluca Store Commerce');

/**
 * Trans Email
 */
$coreConfig->saveConfig ('trans_email/ident_general/name',  'Toluca Store');
$coreConfig->saveConfig ('trans_email/ident_general/email', 'general@toluca.com.br');

$coreConfig->saveConfig ('trans_email/ident_sales/name',    'Toluca Store');
$coreConfig->saveConfig ('trans_email/ident_sales/email',   'sales@toluca.com.br');

$coreConfig->saveConfig ('trans_email/ident_support/name',  'Toluca Store');
$coreConfig->saveConfig ('trans_email/ident_support/email', 'support@toluca.com.br');

$coreConfig->saveConfig ('trans_email/ident_custom1/name',  'Toluca Store');
$coreConfig->saveConfig ('trans_email/ident_custom1/email', 'custom1@toluca.com.br');

$coreConfig->saveConfig ('trans_email/ident_custom2/name',  'Toluca Store');
$coreConfig->saveConfig ('trans_email/ident_custom2/email', 'custom2@toluca.com.br');

/**
 * Catalog
 */
$coreConfig->saveConfig ('catalog/frontend/list_allow_all', '1');
$coreConfig->saveConfig (Mage_Catalog_Helper_Category_Flat::XML_PATH_IS_ENABLED_FLAT_CATALOG_CATEGORY, '1');
$coreConfig->saveConfig (Mage_Catalog_Helper_Product_Flat::XML_PATH_USE_PRODUCT_FLAT,                  '1');

$coreConfig->saveConfig ('catalog/placeholder/image_placeholder',       'default/image.jpg');
$coreConfig->saveConfig ('catalog/placeholder/small_image_placeholder', 'default/small_image.jpg');
$coreConfig->saveConfig ('catalog/placeholder/thumbnail_placeholder',   'default/thumbnail.jpg');

$coreConfig->saveConfig (Mage_Catalog_Helper_Data::XML_PATH_SEO_SAVE_HISTORY, '0');

$coreConfig->saveConfig (Mage_CatalogSearch_Model_Fulltext::XML_PATH_CATALOG_SEARCH_TYPE, '3');

$coreConfig->saveConfig ('catalog/custom_options/date_fields_order', 'd,m,y');
$coreConfig->saveConfig ('catalog/custom_options/time_format',       '24h');

/**
 * CatalogInventory
 */
$coreConfig->saveConfig (Mage_CatalogInventory_Helper_Data::XML_PATH_SHOW_OUT_OF_STOCK, '1');

$coreConfig->saveConfig ('cataloginventory/item_options/auto_return', '1');

/**
 * Customer
 */
$coreConfig->saveConfig (Mage_Customer_Model_Config_Share::XML_PATH_CUSTOMER_ACCOUNT_SHARE, '0');
$coreConfig->saveConfig (Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN, 'toluca.com.br');
$coreConfig->saveConfig (Mage_Customer_Model_Customer::XML_PATH_IS_CONFIRM, '0');

$coreConfig->saveConfig ('customer/online_customers/online_minutes_interval', '1');
$coreConfig->saveConfig ('customer/password/require_admin_user_to_change_user_password', '0');

$coreConfig->saveConfig ('customer/address/street_lines',    '4');
$coreConfig->saveConfig ('customer/address/prefix_show',     '0');
$coreConfig->saveConfig ('customer/address/middlename_show', '0');
$coreConfig->saveConfig ('customer/address/suffix_show',     '0');

$coreConfig->deleteConfig ('customer/address/dob_show');
$coreConfig->deleteConfig ('customer/address/taxvat_show');
$coreConfig->deleteConfig ('customer/address/gender_show');

/**
 * Wishlist
 */
$coreConfig->saveConfig ('wishlist/general/active', '0');

/**
 * Sales
 */
$coreConfig->saveConfig ('sales/minimum_order/active', '1');
$coreConfig->saveConfig ('sales/minimum_order/amount', '0');

$coreConfig->saveConfig ('sales/gift_options/allow_order', '0');
$coreConfig->saveConfig ('sales/gift_options/allow_items', '0');

/**
 * Sales Email
 */
/*
$coreConfig->saveConfig (Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED,                   '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_ENABLED,            '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_ENABLED,           '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Invoice::XML_PATH_UPDATE_EMAIL_ENABLED,    '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Shipment::XML_PATH_EMAIL_ENABLED,          '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Shipment::XML_PATH_UPDATE_EMAIL_ENABLED,   '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Creditmemo::XML_PATH_EMAIL_ENABLED,        '0');
$coreConfig->saveConfig (Mage_Sales_Model_Order_Creditmemo::XML_PATH_UPDATE_EMAIL_ENABLED, '0');
*/

/**
 * Tax
 */
$coreConfig->saveConfig (Mage_Tax_Model_Config::CONFIG_XML_PATH_DEFAULT_COUNTRY, 'BR');

/**
 * Shipping
 */
$coreConfig->saveConfig (Mage_Shipping_Model_Config::XML_PATH_ORIGIN_COUNTRY_ID, 'BR');

$coreConfig->saveConfig ('shipping/option/checkout_multiple', '0');

/**
 * Admin
 */
$coreConfig->saveConfig (Mage_Admin_Model_User::XML_PATH_STARTUP_PAGE, 'sales/order');

$coreConfig->saveConfig ('admin/security/session_cookie_lifetime',   '86400');
$coreConfig->saveConfig ('admin/security/validate_formkey_checkout', '1');

/**
 * System
 */
$coreConfig->saveConfig ('system/cron/enableRunNow',           '1');
$coreConfig->saveConfig ('system/cron/showCronUserMessage',    '0');
$coreConfig->saveConfig ('system/log/enable_log',              '1');
$coreConfig->saveConfig ('system/adminnotification/use_https', '2');
$coreConfig->saveConfig ('system/backup/enabled',              '1');
$coreConfig->saveConfig ('system/backup/type',                 'db');
$coreConfig->saveConfig ('system/backup/maintenance',          '0');

/**
 * Advanced
 */
$coreConfig->saveConfig ('advanced/modules_disable_output/Mage_Backup', '0');

/**
 * Dev
 */
$coreConfig->saveConfig ('dev/js/merge_files',      '1');
$coreConfig->saveConfig ('dev/css/merge_css_files', '1');

Mage_AdminNotification_Model_Survey::saveSurveyViewed (true);

/**
 * Hints
 */
$coreConfig->saveConfig ('hints/store_switcher/url',     null);
$coreConfig->saveConfig ('hints/store_switcher/enabled', '0');

$installer->endSetup();

