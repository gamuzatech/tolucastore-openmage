<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order PDF API
 */
class Gamuza_Basic_Model_Order_Pdf_Api extends Mage_Api_Model_Resource_Abstract
{
    public function order ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $pdf = Mage::getModel ('basic/sales_order_pdf_order')->getPdf (array ($order));

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    public function invoice ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        $collection = Mage::getModel ('sales/order_invoice')->getCollection ()
            ->addFieldToFilter ('order_id', array ('eq' => $order->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_fault ('invoice_not_exists');
        }

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $result = array ();

        foreach ($collection as $invoice)
        {
            $result [] = $invoice;
        }

        $pdf = Mage::getModel ('sales/order_pdf_invoice')->getPdf ($result);

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    public function shipment ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        $collection = Mage::getModel ('sales/order_shipment')->getCollection ()
            ->addFieldToFilter ('order_id', array ('eq' => $order->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_fault ('shipment_not_exists');
        }

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $result = array ();

        foreach ($collection as $shipment)
        {
            $result [] = $shipment;
        }

        $pdf = Mage::getModel ('sales/order_pdf_shipment')->getPdf ($result);

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    public function creditmemo ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        $collection = Mage::getModel ('sales/order_creditmemo')->getCollection ()
            ->addFieldToFilter ('order_id', array ('eq' => $order->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_fault ('creditmemo_not_exists');
        }

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $result = array ();

        foreach ($collection as $creditmemo)
        {
            $result [] = $creditmemo;
        }

        $pdf = Mage::getModel ('sales/order_pdf_creditmemo')->getPdf ($result);

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    public function service ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        $service = Mage::getModel ('basic/order_service')->load ($order->getId (), 'order_id');

        if (!$service || !$service->getId ())
        {
            $this->_fault ('service_not_exists');
        }

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $pdf = Mage::getModel ('basic/sales_order_pdf_service')->getPdf (array ($service));

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    protected function _getOrder ($incrementId, $protectCode)
    {
        if (empty ($incrementId) || empty ($protectCode))
        {
            $this->_fault ('order_not_specified');
        }

        $order = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('increment_id', array ('eq' => $incrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $protectCode))
            ->getFirstItem ()
        ;

        if (!$order || !$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        return $order;
    }
}

