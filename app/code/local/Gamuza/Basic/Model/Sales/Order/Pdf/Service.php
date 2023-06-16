<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Service PDF model
 */
class Gamuza_Basic_Model_Sales_Order_Pdf_Service extends Gamuza_Basic_Model_Sales_Order_Pdf_Abstract
{
    public function getPdf($services = [])
    {
        $this->_beforeGetPdf();

        $this->_initRenderer('service');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($services as $service)
        {
            if ($service->getStoreId())
            {
                Mage::app()->getLocale()->emulate($service->getStoreId());
                Mage::app()->setCurrentStore($service->getStoreId());
            }

            $page  = $this->newPage();
            $order = $service->getOrder();

            /* Add image */
            $this->insertLogo($page, $service->getStore());

            /* Add address */
            $this->insertAddress($page, $service->getStore());

            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                true
            );

            /* Add document text and number */
            $this->insertDocumentNumber(
                $page,
                sprintf('%s %s', Mage::helper('basic')->__('Service #'), $service->getIncrementId())
            );

            /* Add table */
            $this->_drawHeader($page);

            /* Add body */
            foreach ($order->getAllItems() as $item)
            {
                $item->setOrderItem(Mage::getModel('sales/order_item')->load($item->getId()));

                if ($item->getOrderItem()->getParentItem())
                {
                    continue;
                }

                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }

            /* Add totals */
            $this->insertTotals($page, $service);

            if ($service->getStoreId())
            {
                Mage::app()->getLocale()->revert();
            }
        }

        $this->_afterGetPdf();

        return $pdf;
    }
}

