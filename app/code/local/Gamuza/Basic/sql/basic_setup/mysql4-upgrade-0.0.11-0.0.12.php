<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();
/*
Mage::app ()->getLocale ()->setLocale (Mage::helper ('basic')->getLocaleCode ());

Mage::app ()->getTranslator ()->init (Mage_Core_Model_App_Area::AREA_ADMINHTML, true);
*/
$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_PREPARING)
    ->setLabel (Mage::helper ('basic')->__('Preparing'))
    ->assignState (Mage_Sales_Model_Order::STATE_NEW)
    ->save ()
;

$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_PAID)
    ->setLabel (Mage::helper ('basic')->__('Paid'))
    ->assignState (Mage_Sales_Model_Order::STATE_PROCESSING)
    ->assignState (Mage_Sales_Model_Order::STATE_COMPLETE)
    ->save ()
;

$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_SHIPPED)
    ->setLabel (Mage::helper ('basic')->__('Shipped'))
    ->assignState (Mage_Sales_Model_Order::STATE_PROCESSING)
    ->assignState (Mage_Sales_Model_Order::STATE_COMPLETE)
    ->save ()
;

$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_DELIVERED)
    ->setLabel (Mage::helper ('basic')->__('Delivered'))
    ->assignState (Mage_Sales_Model_Order::STATE_COMPLETE)
    ->save ()
;

$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_REFUNDED)
    ->setLabel (Mage::helper ('basic')->__('Refunded'))
    ->assignState (Mage_Sales_Model_Order::STATE_CLOSED)
    ->save ()
;

$orderStatus = Mage::getModel ('sales/order_status')
    ->setStatus (Gamuza_Basic_Helper_Data::ORDER_STATUS_DELIVERED)
    ->setLabel (Mage::helper ('basic')->__('Delivered'))
    ->assignState (Mage_Sales_Model_Order::STATE_COMPLETE)
    ->save ()
;

$installer->endSetup ();

