<?php
/*
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Customer_Model_Entity_Setup('basic_setup');
$installer->startSetup ();

/**
 * Cellphone
 */
$installer->addAttribute(
    'customer',
    Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_CELLPHONE,
    array(
        'type'         => 'varchar',
        'length'       => 255,
        'input'        => 'text',
        'label'        => Mage::helper ('basic')->__('Cellphone'),
        'visible'      => true,
        'required'     => false,
        'user_defined' => false,
        'unique'       => true,
    )
);

$forms = array(
    'adminhtml_customer',
    'adminhtml_checkout',
);

$attribute = Mage::getSingleton ('eav/config')->getAttribute(
    $installer->getEntityTypeId ('customer'), Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_CELLPHONE)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('is_system', true)
    ->setData('sort_order', 72)
;
$attribute->save ();

/**
 * SecondaryName
 */
$installer->addAttribute(
    'customer',
    Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_SECONDARY_NAME,
    array(
        'type'         => 'varchar',
        'length'       => 255,
        'input'        => 'text',
        'label'        => Mage::helper ('basic')->__('Secondary Name'),
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
    $installer->getEntityTypeId ('customer'), Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_SECONDARY_NAME)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('is_system', true)
    ->setData('sort_order', 71)
;
$attribute->save ();

/**
 * Code
 */
$installer->addAttribute(
    'customer',
    Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_CODE,
    array(
        'type'         => 'varchar',
        'length'       => 255,
        'input'        => 'text',
        'label'        => Mage::helper ('basic')->__('Code'),
        'visible'      => true,
        'required'     => false,
        'user_defined' => false,
        'unique'       => true,
    )
);

$forms = array(
    'adminhtml_customer',
);

$attribute = Mage::getSingleton ('eav/config')->getAttribute(
    $installer->getEntityTypeId ('customer'), Gamuza_Basic_Helper_Data::CUSTOMER_ATTRIBUTE_CODE)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('is_system', true)
    ->setData('sort_order', 1100)
;
$attribute->save ();

/**
 * Cellphone
 */
$installer->addAttribute(
    'customer_address',
    Gamuza_Basic_Helper_Data::CUSTOMER_ADDRESS_ATTRIBUTE_CELLPHONE,
    array(
        'type'         => 'varchar',
        'length'       => 255,
        'input'        => 'text',
        'label'        => Mage::helper ('basic')->__('Cellphone'),
        'visible'      => true,
        'required'     => true,
        'user_defined' => false,
        'unique'       => false,
    )
);

$forms = array(
    'adminhtml_customer_address',
    'customer_address_edit',
    'customer_register_address'
);

$attribute = Mage::getSingleton ('eav/config')->getAttribute(
    $installer->getEntityTypeId ('customer_address'), Gamuza_Basic_Helper_Data::CUSTOMER_ADDRESS_ATTRIBUTE_CELLPHONE)
;
$attribute->setData ('used_in_forms', $forms)
    ->setData('validate_rules', array(
        'max_text_length' => 255,
        'min_text_length' => 1
    ))
    ->setData('is_system', true)
    ->setData('sort_order', 115)
;
$attribute->save ();

$this->getConnection ()->addColumn(
    $this->getTable ('sales/quote_address'),
    'cellphone',
    'varchar(255) DEFAULT NULL AFTER telephone'
);

$this->getConnection ()->addColumn(
    $this->getTable ('sales/order_address'),
    'cellphone',
    'varchar(255) DEFAULT NULL AFTER telephone'
);

$installer->endSetup ();

