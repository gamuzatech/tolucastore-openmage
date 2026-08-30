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

        if (intval ($order->getCustomerId ()) > 0)
        {
            $customerId = $order->getCustomerId ();
        }

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
            Mage::getModel ('checkout/cart_customer_api')->setAddresses ($quote->getId (), array(
                array(
                    'mode'       => 'billing',
                    'firstname'  => $order->getBillingAddress ()->getFirstname (),
                    'lastname'   => $order->getBillingAddress ()->getLastname (),
                    'street'     => $order->getBillingAddress ()->getStreet (),
                    'city'       => $order->getBillingAddress ()->getCity (),
                    'region'     => $order->getBillingAddress ()->getRegionId (),
                    'country_id' => $order->getBillingAddress ()->getCountryId (),
                    'postcode'   => preg_replace ('[\D]', null, $order->getBillingAddress ()->getPostcode ()),
                    'cellphone'  => preg_replace ('[\D]', null, $order->getBillingAddress ()->getCellphone ()),
                ),
            ), Mage_Core_Model_App::DISTRO_STORE_ID);

            if ($order->getShippingAddress () && intval ($order->getShippingAddress ()->getId ()) > 0)
            {
                Mage::getModel ('checkout/cart_customer_api')->setAddresses ($quote->getId (), array(
                    array(
                        'mode'       => 'shipping',
                        'firstname'  => $order->getShippingAddress ()->getFirstname (),
                        'lastname'   => $order->getShippingAddress ()->getLastname (),
                        'street'     => $order->getShippingAddress ()->getStreet (),
                        'city'       => $order->getShippingAddress ()->getCity (),
                        'region'     => $order->getShippingAddress ()->getRegionId (),
                        'country_id' => $order->getShippingAddress ()->getCountryId (),
                        'postcode'   => preg_replace ('[\D]', null, $order->getShippingAddress ()->getPostcode ()),
                        'cellphone'  => preg_replace ('[\D]', null, $order->getShippingAddress ()->getCellphone ()),
                    ),
                ), Mage_Core_Model_App::DISTRO_STORE_ID);
            }

            foreach ($order->getAllVisibleItems () as $item)
            {
                $request = new Varien_Object ();

                $product = $item->getProduct ();

                $itemUniqueId = $item->getData (Toluca_PDV_Helper_Data::ORDER_ITEM_ATTRIBUTE_UNIQUE_ID);

                if (!empty ($itemUniqueId))
                {
                    $product->addCustomOption ('unique_id', $itemUniqueId);
                }

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

                $quoteItem = $quote->addProduct ($product, $request);

                if (is_string ($quoteItem))
                {
                    Mage::throwException ($quoteItem);
                }

                $itemIsPrinted = $item->getData (Toluca_PDV_Helper_Data::ORDER_ITEM_ATTRIBUTE_IS_PRINTED);
                $itemPrinterId = $item->getData (Toluca_PDV_Helper_Data::ORDER_ITEM_ATTRIBUTE_PRINTER_ID);

                $quoteItem->setData (Toluca_PDV_Helper_Data::ORDER_ITEM_ATTRIBUTE_IS_PRINTED, $itemIsPrinted)
                    ->setData (Toluca_PDV_Helper_Data::ORDER_ITEM_ATTRIBUTE_PRINTER_ID, $itemPrinterId)
                    ->save ()
                ;

                if ($quote->getIsSuperMode ())
                {
                    if (!empty ($itemUniqueId))
                    {
                        $option = Mage::getModel ('sales/quote_item_option')
                            ->setCode ('unique_id')
                            ->setValue ($itemUniqueId)
                            ->setItem ($quoteItem)
                            ->setProduct ($product)
                        ;

                        $quoteItem->addOption ($option)
                            ->setUniqueId ($itemUniqueId)
                            ->setIsSuperMode(true)
                            ->save ()
                        ;
                    }
                }
            }

            $quote->collectTotals ()->save ();
        }
        catch (Exception $e)
        {
            $this->_fault ('create_quote_fault', $e->getMessage ());
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

