<?php
/*
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Customer_Model_Entity_Setup('brazil_setup');
$installer->startSetup ();

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

