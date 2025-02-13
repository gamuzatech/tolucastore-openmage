<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * History Pdf default items renderer
 */
class Toluca_PDV_Model_Pdf_Items_History_Default
    extends Mage_Sales_Model_Order_Pdf_Items_Abstract
{
    /**
     * Draw process
     */
    public function draw ()
    {
        $grid = $this->getGrid ();
        $item = $this->getItem ();
        $pdf  = $this->getPdf ();
        $page = $this->getPage ();

        $feed = 35;

        /**
         * draw OpenedAt
         */
        $lines = array ();

        $lines [0] = array(array(
            'text'   => Mage::helper ('core')->formatDate ($item->getOpenedAt ()),
            'feed'   => $feed,
        ));

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 20,
        );

        $page = $pdf->drawLineBlocks ($page, array ($lineBlock), array ('table_header' => true));

        $page->drawLine ($feed, $pdf->y + 15, $pdf->x - $feed, $pdf->y + 15);

        /**
         * draw Amounts
         */
        $fields = array(
            Mage::helper ('core')->__('Money') => $item->getMoneyAmount () + $item->getChangeAmount (),
            Mage::helper ('core')->__('Machine') => $item->getMachineAmount (),
            Mage::helper ('core')->__('PagCripto') => $item->getPagcriptoAmount (),
            Mage::helper ('core')->__('PicPay') => $item->getPicpayAmount (),
            Mage::helper ('core')->__('OpenPix') => $item->getOpenpixAmount (),
            Mage::helper ('core')->__('Credit Card') => $item->getCreditcardAmount (),
            Mage::helper ('core')->__('Billet') => $item->getBilletAmount (),
            Mage::helper ('core')->__('Bank Transfer') => $item->getBanktransferAmount (),
            Mage::helper ('core')->__('Check Money') => $item->getCheckAmount (),
            Mage::helper ('core')->__('Pix') => $item->getPixAmount (),
        );

        $fields = array_filter ($fields, function ($value) {
            return floatval ($value) > 0.0000;
        });

        $total = array_sum (array_filter ($fields, function ($value) {
            return floatval ($value) > 0.0000;
        }));

        $pdf->total += $total;

        $i = 0;
        $lines = array ();

        foreach ($fields as $text => $value)
        {
            $lines [$i][] = array(
                'text' => $text,
                'feed' => $feed,
            );

            $lines [$i][] = array(
                'text'   => Mage::helper ('core')->formatPrice ($value, false),
                'feed'   => $feed + 200,
            );

            $lines [$i][] = array(
                'text'   => sprintf ('%.2f%%', ($value / $total) * 100),
                'feed'   => $feed + 300,
            );

            $i ++;
        }

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 20,
        );

        $page = $pdf->drawLineBlocks ($page, array ($lineBlock), array ('table_header' => true));

        $page->drawLine ($feed, $pdf->y + 15, $pdf->x - $feed, $pdf->y + 15);

        /**
         * draw Total
         */
        $lines = array ();

        $lines [0] = array(array(
            'text'   => Mage::helper ('core')->__('Total'),
            'feed'   => $feed,
        ));

        $lines [0][] = array(
            'text'   => Mage::helper ('core')->formatPrice ($total, false),
            'feed'   => $feed + 200,
        );

        $lines [1][] = array(
            'text'   => " ",
            'feed'   => $feed,
        );

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 20,
        );

        $page = $pdf->drawLineBlocks ($page, array ($lineBlock), array ('table_header' => true));

        /**
         * draw GrandTotal
         */
        if ($item == $grid->getCollection ()->getLastItem ())
        {
            $page->drawLine ($feed, $pdf->y + 15, $pdf->x - $feed, $pdf->y + 15);

            $lines = array ();

            $lines [0] = array(array(
                'text'   => Mage::helper ('core')->__('Grand Total'),
                'feed'   => $feed,
            ));

            $lines [0][] = array(
                'text'   => Mage::helper ('core')->formatPrice ($pdf->total, false),
                'feed'   => $feed + 200,
            );

            $lineBlock = array(
                'lines'  => $lines,
                'height' => 20,
            );

            $page = $pdf->drawLineBlocks ($page, array ($lineBlock), array ('table_header' => true));
        }

        $this->setPage ($page);
    }
}

