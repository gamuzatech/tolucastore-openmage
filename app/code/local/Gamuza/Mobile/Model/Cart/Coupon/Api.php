<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Shopping cart api
 */
class Gamuza_Mobile_Model_Cart_Coupon_Api extends Mage_Checkout_Model_Cart_Coupon_Api
{
    use Gamuza_Mobile_Trait_Api_Resource;

    /**
     * @param  $quoteId
     * @param  $couponCode
     * @param  $storeId
     * @return bool
     */
    public function addCoupon($code = null, $couponCode = null, $store = null)
    {
        return $this->applyCoupon($code, $couponCode, $store);
    }

    /**
     * @param  $quoteId
     * @param  $storeId
     * @return void
     */
    public function removeCoupon($code = null, $store = null)
    {
        $couponCode = '';

        return $this->applyCoupon($code, $couponCode, $store);
    }

    /**
     * @param  $quoteId
     * @param  $storeId
     * @return string
     */
    public function getCoupon($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        return $quote->getCouponCode();
    }

    /**
     * @param  $quoteId
     * @param  $couponCode
     * @param  $store
     * @return bool
     */
    protected function applyCoupon($code = null, $couponCode = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        if (!$quote->getItemsCount())
        {
            $this->_fault('quote_is_empty');
        }

        $oldCouponCode = $quote->getCouponCode();

        if (!is_string($couponCode) || (!strlen($couponCode) && !strlen($oldCouponCode)))
        {
            return false;
        }

        try
        {
            $quote->getShippingAddress()->setCollectShippingRates(true);

            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
                ->collectTotals()
                ->save()
            ;
        }
        catch (Exception $e)
        {
            $this->_fault("cannot_apply_coupon_code", $e->getMessage());
        }

        if ($couponCode)
        {
            if (!$couponCode == $quote->getCouponCode())
            {
                $this->_fault('coupon_code_is_not_valid');
            }
        }

        return true;
    }
}

