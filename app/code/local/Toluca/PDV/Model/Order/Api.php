<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order API
 */
class Toluca_PDV_Model_Order_Api extends Mage_Api_Model_Resource_Abstract
{
    /**
     * Reorder order information
     *
     * @param  string $orderIncrementId
     * @param  string $store
     * @return boolean
     */
    public function reorder ($orderIncrementId = null, $orderProtectCode = null)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        /**
         * getCustomerEmail
         */
        Mage::app ()->getStore ()->setConfig (
            Toluca_PDV_Helper_Data::XML_PATH_DEFAULT_EMAIL_PREFIX, 'pdv'
        );

        $cashierId  = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CASHIER_ID);
        $operatorId = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_OPERATOR_ID);
        $customerId = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID);
        $tableId    = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_TABLE_ID);
        $cardId     = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CARD_ID);

        $quoteId = Mage::getModel ('pdv/cart_api')->create ($cashierId, $operatorId, $customerId, 0, $tableId, $cardId);

        $quote = Mage::getModel ('sales/quote')
            ->setStoreId (Mage_Core_Model_App::DISTRO_STORE_ID)
            ->load ($quoteId)
            /*
            ->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_IS_COMANDA, '0')
            ->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_IS_PRINTED, '1')
            */
            ->setCustomerNote ($order->getCustomerNote ())
            ->save ()
        ;

        try
        {
            foreach ($order->getAllVisibleItems () as $item)
            {
                $request = new Varien_Object ();

                $productOptions = $item->getProductOptions ();

                if (array_key_exists ('info_buyRequest', $productOptions))
                {
                    $buyRequest = $productOptions ['info_buyRequest'];

                    if (array_key_exists ('qty', $buyRequest))
                    {
                        $request->setData ('qty', $buyRequest ['qty']);
                    }

                    if (array_key_exists ('options', $buyRequest))
                    {
                        $request->setData ('options', $buyRequest ['options']);
                    }

                    if (array_key_exists ('additional_options', $buyRequest))
                    {
                        $request->setData ('additional_options', $buyRequest ['additional_options']);
                    }

                    if (array_key_exists ('super_attribute', $buyRequest))
                    {
                        $request->setData ('super_attribute', $buyRequest ['super_attribute']);
                    }

                    if (array_key_exists ('bundle_option', $buyRequest))
                    {
                        $request->setData ('bundle_option', $buyRequest ['bundle_option']);
                    }
                }

                $quote->addProduct ($item->getProduct (), $request);
            }

            $quote->collectTotals ()->save ();
        }
        catch (Exception $e)
        {
            // nothing
        }

        return intval ($quote->getId ());
    }

    /**
     * Initialize basic order model
     *
     * @param mixed $orderIncrementId
     * @param mixed $orderProtectCode
     * @return Mage_Sales_Model_Order
     */
    protected function _initOrder ($orderIncrementId, $orderProtectCode = null)
    {
        $order = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('increment_id', array ('eq' => $orderIncrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $orderProtectCode))
            ->getFirstItem ()
        ;

        if (!$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        return $order;
    }
}

