<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Quote Draft PDF model
 */
class Gamuza_Basic_Model_Sales_Quote_Pdf_Draft extends Gamuza_Basic_Model_Sales_Quote_Pdf_Abstract
{
    public function getPdf($drafts = [])
    {
        $this->_beforeGetPdf();

        $this->_initRenderer('quote_draft');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($drafts as $draft)
        {
            if ($draft->getStoreId())
            {
                Mage::app()->getLocale()->emulate($draft->getStoreId());
                Mage::app()->setCurrentStore($draft->getStoreId());
            }

            $page  = $this->newPage();
            $order = $draft->getOrder();

            if (!$order->getRealOrderId())
            {
                $order->setRealOrderId(sprintf('%s %s %s', str_repeat('-', 8), Mage::helper('basic')->__('Quote #'), $draft->getQuoteId()));
            }

            /* Add image */
            $this->insertLogo($page, $draft->getStore());

            /* Add address */
            $this->insertAddress($page, $draft->getStore());

            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                true
            );

            /* Add document text and number */
            $this->insertDocumentNumber(
                $page,
                sprintf('%s %s', Mage::helper('basic')->__('Quote Draft #'), $draft->getIncrementId())
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
            $this->insertTotals($page, $draft);

            if ($draft->getStoreId())
            {
                Mage::app()->getLocale()->revert();
            }
        }

        $this->_afterGetPdf();

        return $pdf;
    }
}

