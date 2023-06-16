<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Helper_Message extends Mage_Core_Helper_Abstract
{
    const UNAVAILABLE_AT_THE_MOMENT  = 'unavailable_at_the_moment';
    const THIS_OUR_OPENING_HOURS     = 'this_our_opening_hours';
    const BUY_THROUGH_THE_APP        = 'buy_through_the_app';
    const PLEASE_WAIT_AN_ATTENDANT   = 'please_wait_an_attendant';
    const PLEASE_ENTER_THE_ADDRESS   = 'please_enter_the_address';
    const ADD_COMMENT_FOR_PRODUCT    = 'add_comment_for_product';
    const PRODUCT_ADDED_TO_CART      = 'product_added_to_cart';
    const PRODUCT_NOT_ADDED_TO_CART  = 'product_not_added_to_cart';
    const ENTER_DELIVERY_METHOD      = 'enter_delivery_method';
    const NO_DELIVERY_METHOD_FOUND   = 'no_delivery_method_found';
    const ENTER_PAYMENT_METHOD       = 'enter_payment_method';
    const NO_PAYMENT_METHOD_FOUND    = 'no_payment_method_found';
    const ENTER_TO_CONFIRM_ORDER     = 'enter_to_confirm_order';

    const ENTER_CATEGORY_CODE        = 'enter_category_code';
    const ENTER_ZAP_TO_ATTENDANT     = 'enter_zap_to_attendant';
    const ENTER_PRODUCT_CODE_TO_CART = 'enter_product_code_to_cart';
    const CHOOSE_OPTION_FOR_PRODUCT  = 'choose_options_for_product';
    const ENTER_PRODUCT_OPTION_CODE  = 'enter_product_option_code';
    const THIS_IS_YOUR_SHOPPING_CART = 'this_is_your_shopping_cart';

    const DEFAULT_APP_URL = 'https://app.toluca.com.br';

    public function getUnavailableAtTheMomentText ()
    {
        return $this->getText (self::UNAVAILABLE_AT_THE_MOMENT);
    }

    public function getThisOurOpeningHoursText ()
    {
        return $this->getText (self::THIS_OUR_OPENING_HOURS);
    }

    public function getBuyThroughTheAppText ()
    {
        return $this->getText (self::BUY_THROUGH_THE_APP);
    }

    public function getPleaseWaitAnAttendantText ()
    {
        return $this->getText (self::PLEASE_WAIT_AN_ATTENDANT);
    }

    public function getPleaseEnterTheAddressText ()
    {
        return $this->getText (self::PLEASE_ENTER_THE_ADDRESS);
    }

    public function getAddCommentForProductText ()
    {
        return $this->getText (self::ADD_COMMENT_FOR_PRODUCT);
    }

    public function getProductAddedToCartText ()
    {
        return $this->getText (self::PRODUCT_ADDED_TO_CART);
    }

    public function getProductNotAddedToCartText ()
    {
        return $this->getText (self::PRODUCT_NOT_ADDED_TO_CART);
    }

    public function getEnterDeliveryMethodText ()
    {
        return $this->getText (self::ENTER_DELIVERY_METHOD);
    }

    public function getNoDeliveryMethodFoundText ()
    {
        return $this->getText (self::NO_DELIVERY_METHOD_FOUND);
    }

    public function getEnterPaymentMethodText ()
    {
        return $this->getText (self::ENTER_PAYMENT_METHOD);
    }

    public function getNoPaymentMethodFoundText ()
    {
        return $this->getText (self::NO_PAYMENT_METHOD_FOUND);
    }

    public function getEnterToConfirmOrderText ()
    {
        return $this->getText (self::ENTER_TO_CONFIRM_ORDER);
    }

    public function getEnterCategoryCodeText ()
    {
        return $this->getText (self::ENTER_CATEGORY_CODE);
    }

    public function getEnterZapToAttendantText ()
    {
        return $this->getText (self::ENTER_ZAP_TO_ATTENDANT);
    }

    public function getEnterProductCodeToCartText ()
    {
        return $this->getText (self::ENTER_PRODUCT_CODE_TO_CART);
    }

    public function getChooseOptionForProductText ()
    {
        return $this->getText (self::CHOOSE_OPTION_FOR_PRODUCT);
    }

    public function getEnterProductOptionCodeText ()
    {
        return $this->getText (self::ENTER_PRODUCT_OPTION_CODE);
    }

    public function getThisIsYourShoppingCartText ()
    {
        return $this->getText (self::THIS_IS_YOUR_SHOPPING_CART);
    }

    private function getText ($code)
    {
        $result = null;

        switch ($code)
        {
            case self::UNAVAILABLE_AT_THE_MOMENT:
            {
                $result = $this->__('Unfortunately we are not available at the moment.') . ' 😕 ';

                break;
            }
            case self::THIS_OUR_OPENING_HOURS:
            {
                $result = $this->__('This is our opening hours:');

                break;
            }
            case self::BUY_THROUGH_THE_APP:
            {
/*
                $result = $this->__('Buy also through the *Toluca Store* app at app.toluca.com.br');

                $storeUrl = Mage::app ()
                    ->getStore (Mage_Core_Model_App::DISTRO_STORE_ID)
                    ->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_LINK)
                ;
*/
                $storeUrl    = sprintf ('%s/express?mode=list', Mage::getStoreConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_INFORMATION_STORE_URL));
                $whatsappUrl = sprintf ('%s?text=%s', Mage::getStoreConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_INFORMATION_WHATSAPP_URL), $this->__('hi'));

                $result = sprintf (
                    "%s: %s\n\n%s: %s\n\n%s: %s",
                    $this->__('APP'),   self::DEFAULT_APP_URL,
                    $this->__('Store'), $storeUrl,
                    $this->__('Robot'), $whatsappUrl,
                );

                break;
            }
            case self::PLEASE_WAIT_AN_ATTENDANT:
            {
                $result = $this->__('Please wait. Soon an attendant will answer you ...');

                break;
            }
            case self::PLEASE_ENTER_THE_ADDRESS:
            {
                $result = $this->__('Please enter the *street*, the *number* and the *district* where you live:') . PHP_EOL . PHP_EOL
                    . $this->__('Example: Address Flowers 123 Zilda Village')
                ;

                break;
            }
            case self::ADD_COMMENT_FOR_PRODUCT:
            {
                $result = $this->__('Would you like to add any notes for this product?') . PHP_EOL . PHP_EOL
                    . $this->__('Please enter any notes:') . PHP_EOL . PHP_EOL
                    . $this->getTypeCommandToContinueText (Toluca_Bot_Model_Chat_Api::COMMAND_ZERO)
                ;

                break;
            }
            case self::PRODUCT_ADDED_TO_CART:
            {
                $result = '*' . $this->__('Product successfully added to your cart!') . '*';

                break;
            }
            case self::PRODUCT_NOT_ADDED_TO_CART:
            {
                $result = '*' . $this->__('It was not possible to add the product to the cart!') . '*';

                break;
            }
            case self::ENTER_DELIVERY_METHOD:
            {
                $result = $this->__('Please enter the code of the delivery method:');

                break;
            }
            case self::NO_DELIVERY_METHOD_FOUND:
            {
                $result = $this->__('No delivery method was found for your address!');

                break;
            }
            case self::ENTER_PAYMENT_METHOD:
            {
                $result = $this->__('Please enter the code of the payment method:');

                break;
            }
            case self::NO_PAYMENT_METHOD_FOUND:
            {
                $result = $this->__('No payment method was found for your address!');

                break;
            }
            case self::ENTER_TO_CONFIRM_ORDER:
            {
                $result = $this->__('Please enter *ok* to confirm your order:');

                break;
            }
            case self::ENTER_CATEGORY_CODE:
            {
                $result = $this->__('Please enter the category code to continue:');

                break;
            }
            case self::ENTER_ZAP_TO_ATTENDANT:
            {
                $result = $this->__('Or at any time enter *%s* to call the attendant.', Toluca_Bot_Helper_Data::STATUS_ZAP);

                break;
            }
            case self::ENTER_PRODUCT_CODE_TO_CART:
            {
                $result = $this->__('Please enter the product code to insert into the cart:');

                break;
            }
            case self::CHOOSE_OPTION_FOR_PRODUCT:
            {
                $result = $this->__('Would you like to choose any option for this product?');

                break;
            }
            case self::ENTER_PRODUCT_OPTION_CODE:
            {
                $result = $this->__('Please enter the product option code:');

                break;
            }
            case self::THIS_IS_YOUR_SHOPPING_CART:
            {
                $result = $this->__('This is your shopping cart:');

                break;
            }
            default:
            {
                // nothing

                break;
            }
        }

        return $result;
    }

    public function getGreetingText ($name)
    {
        $hour = intval (Mage::getModel ('core/date')->date ('H'));

        if ($hour > 0 && $hour < 12)
        {
            $text = 'Good morning';
        }
        else if ($hour >= 12 && $hour < 18)
        {
            $text = 'Good afternoon';
        }
        else
        {
            $text = 'Good evening';
        }

        return sprintf ('*%s* %s! *%s*?', $this->__($text), $name, $this->__('All right'));
    }

    public function getWelcomeText ()
    {
        $storeName = Mage::getStoreConfig (Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);

        return $this->__('*Welcome* to the robot of *%s*!', $storeName);
    }

    public function getTypeCommandToContinueText ($command)
    {
        return $this->__('Or type *%s* to continue:', $command);
    }

    public function getTypeCommandToGoToCartText ($command)
    {
        return $this->__('Or type *%s* to go to cart:', $command);
    }

    public function getProductsForCategoryText ($categoryName)
    {
        return $this->__('Here is the list of our products for category *%s*:', $categoryName);
    }

    public function getTypeListToCategoriesText ($command)
    {
        return $this->__('Or type *%s* to list the categories again:', $command);
    }

    public function getTypeClearToRestartText ($command)
    {
        return $this->__('Or type *%s* to clean your shopping cart:', $command);
    }

    public function getEnterValuesCodesToOptionText ($optionTitle)
    {
        return $this->__('Please enter the values codes to option: *%s*', $optionTitle) . PHP_EOL . PHP_EOL
            . $this->__('Example: 1 5 10 15')
        ;
    }

    public function getEnterOneValueCodeToOptionText ($optionTitle)
    {
        return $this->__('Please enter only one value code to option: *%s*', $optionTitle) . PHP_EOL . PHP_EOL
            . $this->__('Example: 1')
        ;
    }

    public function getEnterAmountForMoneyChangeText ()
    {
        return $this->__('Please enter the amount of the money change for your order:') . PHP_EOL . PHP_EOL
            . $this->__('Example: 50')
        ;
    }

    public function getNeedChangeForMoneyText ($paymentChange, $paymentCash)
    {
        $paymentChange = $paymentChange == '1'
            ? Mage::helper ('bot')->__('Yes')
            : Mage::helper ('bot')->__('No')
        ;

        $paymentCash = Mage::helper ('core')->currency ($paymentCash, true, false);

        return $this->__("Need change for money: %s", $paymentChange) . PHP_EOL
             . $this->__("Change to amount: %s", $paymentCash)
        ;
    }

    public function getChooseTypeOfCardText ()
    {
        return $this->__('Please choose the type of card:');
    }

    public function getChooseTypeOfCriptoText ()
    {
        return $this->__('Please choose the type of cripto:');
    }

    public function getCardTypeForMachineText ($cctype)
    {
        $result = null;

        switch ($cctype)
        {
            case 'AE':  { $result = 'American Express';     break; }
            case 'AL':  { $result = 'Alelo';                break; }
            case 'AU':  { $result = 'Aura';                 break; }
            case 'BC':  { $result = 'Banri Compras Débito'; break; }
            case 'CC':  { $result = 'Cabal Débito';         break; }
            case 'DC':  { $result = 'Diners Club';          break; }
            case 'DI':  { $result = 'Discover';             break; }
            case 'EC':  { $result = 'Elo Crédito';          break; }
            case 'ED':  { $result = 'Elo Débito';           break; }
            case 'ELO': { $result = 'Elo';                  break; }
            case 'HC':  { $result = 'Hipercard';            break; }
            case 'JCB': { $result = 'JCB';                  break; }
            case 'HI':  { $result = 'Hiper';                break; }
            case 'MC':  { $result = 'Mastercard';           break; }
            case 'SM':  { $result = 'Maestro / Switch';     break; }
            case 'SO':  { $result = 'Sodexo';               break; }
            case 'TI':  { $result = 'Ticket';               break; }
            case 'VE':  { $result = 'Visa Electron';        break; }
            case 'VI':  { $result = 'Visa';                 break; }
            case 'VR':  { $result = 'Vale Refeição';        break; }
        }

        return $this->__('Type of card: %s', $result);
    }

    public function getCurrencyTypeForCriptoText ($cctype)
    {
        $result = null;

        switch ($cctype)
        {
            case 'BCH':  { $result = 'Bitcoin Cash'; break; }
            case 'BNB':  { $result = 'Binance Coin'; break; }
            case 'BUSD': { $result = 'Binance USD';  break; }
            case 'BTC':  { $result = 'Bitcoin';      break; }
            case 'DASH': { $result = 'Dash';         break; }
            case 'DOGE': { $result = 'Dogecoin';     break; }
            case 'ETH':  { $result = 'Ethereum';     break; }
            case 'LTC':  { $result = 'Litecoin';     break; }
            case 'NANO': { $result = 'Nano';         break; }
            case 'USDC': { $result = 'USD Coin';     break; }
            case 'USDT': { $result = 'USD Tether';   break; }
        }

        return $this->__('Type of cripto: %s', $result);
    }

    public function getThankYouForShoppingText ($storeName)
    {
        return $this->__('Thank you for shopping at store *%s*!', $storeName);
    }

    public function getYourOrderNumberText ($order)
    {
        return $this->__('Your order number is *%s-%s*', $order->getIncrementId (), $order->getProtectCode ());
    }

    public function getOrderInformationText ($order)
    {
        $result = null;

        $payment = $order->getPayment ();

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PagCripto')
            && !strcmp ($payment->getMethod (), Gamuza_PagCripto_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('pagcripto/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result .= $this->__('Copy the address to make the payment via %s:', $transaction->getCurrency ()) . PHP_EOL . PHP_EOL
                    . $transaction->getAddress () . PHP_EOL . PHP_EOL
                    . $this->__('Amount') . PHP_EOL . PHP_EOL
                    . $transaction->getAmount () . PHP_EOL . PHP_EOL
                ;
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PicPay')
            && !strcmp ($payment->getMethod (), Gamuza_PicPay_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('picpay/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result .= $this->__('Click on the link to make the payment via PicPay:') . PHP_EOL . PHP_EOL
                    . $transaction->getPaymentUrl () . PHP_EOL . PHP_EOL
                ;
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_OpenPix')
            && !strcmp ($payment->getMethod (), Gamuza_OpenPix_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('openpix/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result .= $this->__('Click on the link to make the payment via OpenPix:') . PHP_EOL . PHP_EOL
                    . $transaction->getPaymentLinkUrl () . PHP_EOL . PHP_EOL
                ;
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('RicardoMartins_PagSeguroPro')
            && !strcmp ($payment->getMethod (), RicardoMartins_PagSeguroPro_Model_Payment_Boleto::CODE))
        {
            $result .= $this->__('Click on the link to make the payment via PagSeguro:') . PHP_EOL . PHP_EOL
                . $payment->getAdditionalInformation ('boletoUrl') . PHP_EOL . PHP_EOL
            ;
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_Brazil')
            && !strcmp ($payment->getMethod (), Gamuza_Brazil_Model_Payment_Method_Pix::CODE))
        {
            $result .= $this->__('Here are the payment instructions:') . PHP_EOL . PHP_EOL
                . Mage::getStoreConfig ('payment/gamuza_brazil_pix/instructions', $order->getStoreId ()) . PHP_EOL . PHP_EOL
            ;
        }

        if ($order->getPayment ()->getMethod () == Mage_Payment_Model_Method_Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE)
        {
            $result .= $this->__('Here are the payment instructions:') . PHP_EOL . PHP_EOL
                . Mage::getStoreConfig ('payment/banktransfer/instructions', $order->getStoreId ()) . PHP_EOL . PHP_EOL
            ;
        }

        return $result;
    }
}

