<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/**
 * Order information for print
 *
 * @package    Mage_Sales
 */
class Mage_Sales_Block_Order_Print extends Mage_Sales_Block_Items_Abstract
{
    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        /** @var Mage_Page_Block_Html_Head $headBlock */
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Print Order # %s', $this->getOrder()->getRealOrderId()));
        }

        /** @var Mage_Payment_Helper_Data $helper */
        $helper = $this->helper('payment');
        $this->setChild(
            'payment_info',
            $helper->getInfoBlock($this->getOrder()->getPayment()),
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getPaymentInfoHtml()
    {
        return $this->getChildHtml('payment_info');
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * @return Mage_Sales_Block_Items_Abstract
     */
    protected function _prepareItem(Mage_Core_Block_Abstract $renderer)
    {
        $renderer->setPrintStatus(true);
        return parent::_prepareItem($renderer);
    }
}
