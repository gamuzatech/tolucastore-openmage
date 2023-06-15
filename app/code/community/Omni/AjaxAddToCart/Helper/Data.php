<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Omni
 * @package     Omni_AjaxAddToCart
 * @copyright   Copyright (c) 2016 Omni Partners Oy (http://www.omnipartners.fi)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Omni_AjaxAddToCart_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_AJAX_ADDTO_ENABLED = 'checkout/cart/ajax_add_to_cart';


    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_AJAX_ADDTO_ENABLED );
    }
}