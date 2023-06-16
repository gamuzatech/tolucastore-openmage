<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Observer
{
    public function salesConvertQuoteItemToOrderItem ($observer)
    {
        $event     = $observer->getEvent ();
        $quoteItem = $event->getItem ();

        if ($additionalOptions = $quoteItem->getBuyRequest ()->getData ('additional_options'))
        {
            $orderItem = $event->getOrderItem ();

            $productOptions = $orderItem->getProductOptions ();
            $productOptions ['additional_options'] = $additionalOptions;

            $orderItem->setProductOptions ($productOptions);
        }
    }
}

