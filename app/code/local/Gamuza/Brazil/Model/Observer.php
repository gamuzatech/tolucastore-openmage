<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Observer
{
    public function salesOrderPlaceAfter (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder ();

        if (in_array ($order->getPayment ()->getMethod (), array (
            Gamuza_Brazil_Model_Payment_Method_Pix::CODE,
        )))
        {
            $order->setData (Gamuza_Brazil_Helper_Data::ORDER_ATTRIBUTE_IS_PIX, true)->save ();
        }
    }
}

