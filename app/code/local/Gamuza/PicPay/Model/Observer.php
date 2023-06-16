<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Model_Observer
{
    public function salesOrderPlaceAfter (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder ();

        if (in_array ($order->getPayment ()->getMethod (), array (
            Gamuza_PicPay_Model_Payment_Method_Payment::CODE,
        )))
        {
            $order->setData (Gamuza_PicPay_Helper_Data::ORDER_ATTRIBUTE_IS_PICPAY, true)->save ();
        }
    }
}

