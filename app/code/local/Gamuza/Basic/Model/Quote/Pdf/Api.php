<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Quote PDF API
 */
class Gamuza_Basic_Model_Quote_Pdf_Api extends Mage_Api_Model_Resource_Abstract
{
    public function draft ($quoteId)
    {
        $quote = $this->_getQuote ($quoteId);

        /*
        $draft = Mage::getModel ('basic/quote_draft')->load ($quote->getId (), 'quote_id');

        if (!$draft || !$draft->getId ())
        {
            $this->_fault ('draft_not_exists');
        }
        */

        $draft = Mage::getModel ('basic/quote_draft')
            ->setQuote($quote)
            ->setState (Gamuza_Basic_Model_Quote_Draft::STATE_OPEN)
            ->save()
        ;

        $quote->setData (Gamuza_Basic_Helper_Data::QUOTE_ATTRIBUTE_IS_DRAFT, true)
            ->setData (Gamuza_Basic_Helper_Data::QUOTE_ATTRIBUTE_DRAFT_ID, $draft->getId ())
            ->save ()
        ;

        $order = Mage::getModel ('sales/order')->load ($quote->getId (), 'quote_id');

        if ($order && $order->getId () > 0)
        {
            $draft->setOrder ($order)->save ();
        }

        $emulation = Mage::getModel ('core/app_emulation');

        $oldEnvironment = $emulation->startEnvironmentEmulation(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage_Core_Model_App_Area::AREA_ADMINHTML,
            true
        );

        $pdf = Mage::getModel ('basic/sales_quote_pdf_draft')->getPdf (array ($draft));

        $emulation->stopEnvironmentEmulation($oldEnvironment);

        return base64_encode ($pdf->render ());
    }

    protected function _getQuote ($quoteId)
    {
        if (empty ($quoteId))
        {
            $this->_fault ('quote_not_specified');
        }

        $quote = Mage::getModel ('sales/quote')
            ->setStoreId (Mage_Core_Model_App::DISTRO_STORE_ID)
            ->load ($quoteId)
        ;

        if (!$quote || !$quote->getId ())
        {
            $this->_fault ('quote_not_exists');
        }

        return $quote;
    }
}

