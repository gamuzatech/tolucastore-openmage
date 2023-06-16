<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog
 */
$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$installer->updateAttribute ('catalog_category', 'custom_use_parent_settings', 'default_value', '1');
$installer->updateAttribute ('catalog_category', 'is_anchor',                  'default_value', '1');
$installer->updateAttribute ('catalog_category', 'page_layout',                'default_value', 'two_columns_left');

$installer->updateAttribute ('catalog_product', 'sku',               'frontend_input', 'label');
$installer->updateAttribute ('catalog_product', 'sku',               'is_used_for_promo_rules', '1');
$installer->updateAttribute ('catalog_product', 'sku',               'is_visible_on_front',     '1');
$installer->updateAttribute ('catalog_product', 'sku',               'used_in_product_listing', '1');
$installer->updateAttribute ('catalog_product', 'description',       'is_required',    '0');
$installer->updateAttribute ('catalog_product', 'short_description', 'frontend_input', 'text');
$installer->updateAttribute ('catalog_product', 'url_key',           'frontend_input', 'label');
$installer->updateAttribute ('catalog_product', 'tax_class_id',      'default_value',  '0');

$resource = Mage::getSingleton ('core/resource');
$write = $resource->getConnection ('core_write');

$productAttributeSortOrder = array(
    'description' => 3,
    'short_description' => 2,
);

foreach ($productAttributeSortOrder as $attributeCode => $sortOrder)
{
    $attributeId = Mage::getSingleton ('eav/config')
        ->getAttribute ('catalog_product', $attributeCode)
        ->getId ()
    ;

    $write->query (sprintf(
        'UPDATE %s SET sort_order = %s WHERE attribute_id = %s LIMIT 1',
        $resource->getTablename ('eav/entity_attribute'),
        $sortOrder, $attributeId
    ));
}

$applyToAttributes = array(
    'price',
    'special_price',
    'special_from_date',
    'special_to_date',
    'cost',
    'group_price',
    'tier_price',
    'minimal_price',
    'country_of_manufacture',
    'msrp_enabled',
    'msrp_display_actual_price_type',
    'msrp',
    'tax_class_id',
);

foreach ($applyToAttributes as $attributeCode)
{
    $installer->updateAttribute ('catalog_product', $attributeCode, 'apply_to', 'simple,configurable,virtual,bundle,downloadable,service');
}

$applyToAttributes = array(
    'is_recurring',
    'recurring_profile',
);

foreach ($applyToAttributes as $attributeCode)
{
    $installer->updateAttribute ('catalog_product', $attributeCode, 'apply_to', 'simple,virtual,service');
}

$rootCategoryId = Mage::getModel ('core/store')
    ->load (Mage_Core_Model_App::DISTRO_STORE_ID)
    ->getRootCategoryId ();

$rootCategory = Mage::getModel ('catalog/category')->load ($rootCategoryId)
    ->setIsAnchor (true)
    ->setPageLayout ('two_columns_left')
    ->save ();

/**
 * Customer
 */
$installer = new Mage_Customer_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

$installer->updateAttribute ('customer', 'prefix',     'is_visible', '0');
$installer->updateAttribute ('customer', 'middlename', 'is_visible', '0');
$installer->updateAttribute ('customer', 'suffix',     'is_visible', '0');

$installer->updateAttribute ('customer', 'dob',    'is_visible', '0');
$installer->updateAttribute ('customer', 'taxvat', 'is_visible', '0');
$installer->updateAttribute ('customer', 'gender', 'is_visible', '0');

$installer->updateAttribute ('customer_address', 'prefix',     'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'middlename', 'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'suffix',     'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'company',    'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'street',     'multiline_count', '4');
$installer->updateAttribute ('customer_address', 'region_id',  'is_required',     '1');
$installer->updateAttribute ('customer_address', 'telephone',  'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'telephone',  'is_required',     '0');
$installer->updateAttribute ('customer_address', 'fax',        'is_visible',      '0');
$installer->updateAttribute ('customer_address', 'fax',        'is_required',     '0');

/**
 * General
 */
$countries = array ('BR');

foreach (Mage::helper('directory')->getCountryCollection() as $country)
{
    if ($country->getRegionCollection()->getSize() > 0)
    {
        $countries[] = $country->getId();
    }
}

$coreConfig = Mage::getModel ('core/config');

$coreConfig->deleteConfig (Mage_Directory_Helper_Data::XML_PATH_STATES_REQUIRED);
$coreConfig->deleteConfig (Mage_Directory_Helper_Data::XML_PATH_DISPLAY_ALL_STATES);

$coreConfig->saveConfig (Mage_Directory_Helper_Data::XML_PATH_STATES_REQUIRED, implode(',', $countries));
$coreConfig->saveConfig (Mage_Directory_Helper_Data::XML_PATH_DISPLAY_ALL_STATES, '1');

/**
 * Translation
 */
$pt_BR_SQL = file_get_contents (Mage::getConfig ()->getOptions ()->getLocaleDir () . DS . Gamuza_Basic_Helper_Data::SQL_PT_BR);

$write->query ($pt_BR_SQL);

$emulation = Mage::getModel ('core/app_emulation');

$oldEnvironment = $emulation->startEnvironmentEmulation(
    Mage_Core_Model_App::DISTRO_STORE_ID,
    Mage_Core_Model_App_Area::AREA_FRONTEND,
    true
);

/**
 * Cms Page  with 'home' identifier page modification for report pages
 */
/** @var Mage_Cms_Model_Page $cms */
$cms = Mage::getModel('cms/page')->load('home', 'identifier');

$homepageTitle = Mage::helper ('page')->__('Toluca Store');

$homepageContent = <<< HOMEPAGE_CONTENT
<!--
<div class="page-title">
<h2>{$homepageTitle}</h2>
</div>
-->
HOMEPAGE_CONTENT;

$reportLayoutUpdate = <<< REPORT_LAYOUT_UPDATE
<reference name="content">
    <block type="catalog/product_new" name="home.catalog.product.new" alias="product_new" template="catalog/product/new.phtml" after="cms_page">
        <action method="addPriceBlockType">
            <type>bundle</type>
            <block>bundle/catalog_product_price</block>
            <template>bundle/catalog/product/price.phtml</template>
        </action>
    </block>
    <block type="basic/catalog_product_category" name="home.catalog.product.category" alias="product_category" template="gamuza/basic/catalog/product/category.phtml" after="product_new">
        <action method="addPriceBlockType">
            <type>bundle</type>
            <block>bundle/catalog_product_price</block>
            <template>bundle/catalog/product/price.phtml</template>
        </action>
    </block>
    <block type="reports/product_viewed" name="home.reports.product.viewed" alias="product_viewed" template="reports/home_product_viewed.phtml" after="product_category">
        <action method="addPriceBlockType">
            <type>bundle</type>
            <block>bundle/catalog_product_price</block>
            <template>bundle/catalog/product/price.phtml</template>
        </action>
    </block>
    <block type="reports/product_compared" name="home.reports.product.compared" template="reports/home_product_compared.phtml" after="product_viewed">
        <action method="addPriceBlockType">
            <type>bundle</type>
            <block>bundle/catalog_product_price</block>
            <template>bundle/catalog/product/price.phtml</template>
        </action>
    </block>
</reference>
REPORT_LAYOUT_UPDATE;

$cms->setLayoutUpdateXml($reportLayoutUpdate)
    ->setRootTemplate('one_column')
    ->setContent($homepageContent)
    ->setTitle($homepageTitle)
    ->save();

/**
 * Cms Block with 'home' identifier
 */

$footerlinksCompany = Mage::helper ('page')->__('Company');
$footerlinksContact = Mage::helper ('page')->__('Contact Us');
$footerlinksTitle   = Mage::helper ('page')->__('Footer Links Contact');

$footerlinksContent = <<< FOOTER_LINKS_CONTENT
<div class="links">
<div class="block-title"><strong><span>{$footerlinksCompany}</span></strong></div>
<ul>
<li><a href="{{store url=""}}contacts/">{$footerlinksContact}</a></li>
</ul>
</div>
FOOTER_LINKS_CONTENT;

/** @var Mage_Cms_Model_Block $cms */
$cms = Mage::getModel('cms/block')
    ->load('footer_links_contact', 'identifier')
    ->setIdentifier('footer_links_contact')
    ->setTitle($footerlinksTitle)
    ->setContent($footerlinksContent)
    ->setStores(array(Mage_Core_Model_App::ADMIN_STORE_ID))
    ->setIsActive(false)
    ->save();

/**
 * Design
 */
$coreConfig->saveConfig ('design/header/welcome', Mage::helper ('basic')->__('Welcome!'));

$copyright = Mage::helper ('basic')->__('Toluca Store&trade; is a trademark of Gamuza Technologies.') . '<br/>'
    . Mage::helper ('basic')->__('Copyright &copy; %s Gamuza Technologies. All rights reserved.', date('Y'));

$coreConfig->saveConfig ('design/footer/copyright', $copyright);

/**
 * Group
 */
$tax = Mage::getResourceModel ('tax/class_collection')
    ->addFieldToFilter ('class_type', Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER)
    ->addFieldToFilter ('class_name', 'Retail Customer')
    ->getFirstItem ()
;

$customerGroups = array(
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_NOT_LOGGED_IN => 'Not Logged In',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_GENERAL => 'General',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_WHOLESALE => 'Wholesale',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_RETAILER => 'Retailer',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_SUPPLIER => 'Supplier',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_MANUFACTURER => 'Manufacturer',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_DISTRIBUTER => 'Distributer',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_SELLER => 'Seller',
    Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_RESELLER => 'Reseller'
);

foreach ($customerGroups as $id => $value)
{
    $name = Mage::helper ('basic')->__($value);

    $write->query (sprintf(
        "UPDATE %s SET name = '%s', is_system = 1 WHERE customer_group_id = %s LIMIT 1",
        $resource->getTablename ('customer/customer_group'),
        $name, $id
    ));
}

/**
 * Attribute
 */
$installer = new Mage_Catalog_Model_Resource_Setup ('basic_setup');

$installer->updateAttribute ('catalog_product', 'weight',        'note', Mage::helper ('basic')->__('Weight in grams or milliliters (numbers only). E.g: 1234'));
$installer->updateAttribute ('catalog_product', 'price',         'note', Mage::helper ('basic')->__('Please use dot (.) for decimal. E.g: 1234.5678'));
$installer->updateAttribute ('catalog_product', 'special_price', 'note', Mage::helper ('basic')->__('Please use dot (.) for decimal. E.g: 1234.5678'));
$installer->updateAttribute ('catalog_product', 'cost',          'note', Mage::helper ('basic')->__('Please use dot (.) for decimal. E.g: 1234.5678'));
$installer->updateAttribute ('catalog_product', 'msrp',          'note', Mage::helper ('basic')->__('Please use dot (.) for decimal. E.g: 1234.5678'));

$emulation->stopEnvironmentEmulation($oldEnvironment);

$installer->endSetup();

