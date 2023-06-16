<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order PDF model
 */
class Gamuza_Basic_Model_Sales_Order_Pdf_Order extends Gamuza_Basic_Model_Sales_Order_Pdf_Abstract
{
    public function getPdf($orders = [])
    {
        $this->_beforeGetPdf();

        $this->_initRenderer('order');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($orders as $order)
        {
            $order->setOrder ($order);

            if ($order->getStoreId())
            {
                Mage::app()->getLocale()->emulate($order->getStoreId());
                Mage::app()->setCurrentStore($order->getStoreId());
            }

            $page  = $this->newPage();

            /* Add image */
            $this->insertLogo($page, $order->getStore());

            /* Add address */
            $this->insertAddress($page, $order->getStore());

            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                true
            );

            /* Add document text and number */
/*
            $this->insertDocumentNumber(
                $page,
                sprintf('%s %s', Mage::helper('basic')->__('Order #'), $order->getIncrementId())
            );
*/
            /* Add table */
            $this->_drawHeader($page);

            /* Add body */
            foreach ($order->getAllItems() as $item)
            {
                $item->setOrderItem($item);

                if ($item->getOrderItem()->getParentItem())
                {
                    continue;
                }

                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }

            /* Add totals */
            $this->insertTotals($page, $order);

            if ($order->getStoreId())
            {
                Mage::app()->getLocale()->revert();
            }
        }

        $this->_afterGetPdf();

        return $pdf;
    }
}

