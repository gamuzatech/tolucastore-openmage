<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Core
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer  = $this;

$configValuesMap = [
    'catalog/productalert/email_stock_template'         => 'catalog_productalert_email_stock_template',
    'catalog/productalert/email_price_template'         => 'catalog_productalert_email_price_template',
    'catalog/productalert_cron/error_email_template'    => 'catalog_productalert_cron_error_email_template',
    'contacts/email/email_template'                     => 'contacts_email_email_template',
    'currency/import/error_email_template'              => 'currency_import_error_email_template',
    'customer/create_account/email_template'            => 'customer_create_account_email_template',
    'customer/password_forgot/email_template'           => 'customer_password_forgot_email_template',
    'newsletter/subscription/confirm_email_template'    => 'newsletter_subscription_confirm_email_template',
    'newsletter/subscription/success_email_template'    => 'newsletter_subscription_success_email_template',
    'newsletter/subscription/un_email_template'         => 'newsletter_subscription_un_email_template',
    'sales_email/order/template'                        => 'sales_email_order_template',
    'sales_email/order_comment/template'                => 'sales_email_order_comment_template',
    'sales_email/invoice/template'                      => 'sales_email_invoice_comment_template',
    'sales_email/invoice_comment/template'              => 'sales_email_invoice_comment_template',
    'sales_email/creditmemo/template'                   => 'sales_email_creditmemo_template',
    'sales_email/creditmemo_comment/template'           => 'sales_email_creditmemo_comment_template',
    'sales_email/shipment/template'                     => 'sales_email_shipment_template',
    'sales_email/shipment_comment/template'             => 'sales_email_shipment_comment_template',
    'sendfriend/email/template'                         => 'sendfriend_email_template',
    'sitemap/generate/error_email_template'             => 'sitemap_generate_error_email_template',
    'wishlist/email/email_template'                     => 'wishlist_email_email_template',
];

foreach ($configValuesMap as $configPath => $configValue) {
    $installer->setConfigData($configPath, $configValue);
}
