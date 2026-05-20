<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Model_Observer
{
    public function salesOrderPlaceAfter (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder ();

        if (in_array ($order->getPayment ()->getMethod (), array (
            Gamuza_Sitef_Model_Payment_Method_Pinpad::CODE,
        )))
        {
            $order->setData (Gamuza_Sitef_Helper_Data::ORDER_ATTRIBUTE_IS_SITEF, true)->save ();
        }
    }
}

