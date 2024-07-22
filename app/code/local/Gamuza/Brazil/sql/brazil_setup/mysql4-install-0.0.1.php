<?php
/*
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Customer_Model_Entity_Setup('brazil_setup');
$installer->startSetup ();

/**
 * brazil_rg_ie
 */
$installer->addAttribute(
    'customer',
    Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_RG_IE,
    array(
        'type'         => 'varchar',
        'length'       => 255,
        'input'        => 'text',
        'label'        => Mage::helper ('brazil')->__('RG / IE'),
        'visible'      => true,
        'required'     => false,
        'user_defined' => false,
        'unique'       => false,
    )
);

$forms = array(
    'adminhtml_customer',
    'adminhtml_checkout',
    'customer_account_create',
    'customer_account_edit',
    'checkout_register',
);

$attribute = Mage::getSingleton ('eav/config')->getAttribute(
    $installer->getEntityTypeId ('customer'), Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_RG_IE)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('is_system', true)
    ->setData('sort_order', 1000)
;
$attribute->save ();

/**
 * brazil_ie_icms
 */
$installer->addAttribute(
    'customer',
    Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_IE_ICMS,
    array(
        'type'         => 'int',
        'length'       => 11,
        'input'        => 'select',
        'label'        => Mage::helper ('brazil')->__('IE / ICMS'),
        'visible'      => true,
        'required'     => false,
        'user_defined' => false,
        'unique'       => false,
        'source'       => 'brazil/eav_entity_attribute_source_ie_icms',
    )
);

$forms = array(
    'adminhtml_customer',
    'adminhtml_checkout',
    'customer_account_create',
    'customer_account_edit',
    'checkout_register',
);

$attribute = Mage::getSingleton ('eav/config')->getAttribute(
    $installer->getEntityTypeId ('customer'), Gamuza_Brazil_Helper_Data::CUSTOMER_ATTRIBUTE_BRAZIL_IE_ICMS)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('is_system', true)
    ->setData('sort_order', 1000)
;
$attribute->save ();

$installer->endSetup ();

