<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer api
 */
class Gamuza_Mobile_Model_Customer_Api extends Mage_Customer_Model_Customer_Api
{
    /**
     * Draft customer
     *
     * @param int $customerId
     * @return string|null
     */
    public function draft ($customerId, $orderIds = null)
    {
        $customer = Mage::getModel ('customer/customer')->load ($customerId);

        if (!$customer || !$customer->getId ())
        {
            $this->_fault ('not_exists');
        }

        $result = Mage::app ()
            ->getLayout ()
            ->createBlock ('mobile/adminhtml_customer_draft')
            ->setArea (Mage_Core_Model_App_Area::AREA_ADMINHTML)
            ->setCustomer ($customer)
            ->setOrderIds ($orderIds)
            ->setTemplate ('gamuza/mobile/customer/draft.phtml')
            ->toHtml ();

        return $result;
    }
}

