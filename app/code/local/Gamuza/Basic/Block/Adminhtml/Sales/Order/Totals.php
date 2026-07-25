<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml order totals block
 */
class Gamuza_Basic_Block_Adminhtml_Sales_Order_Totals
    extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        if (Mage::getStoreConfigFlag(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_ACTIVE))
        {
            $value = 34.56; // Mage::getStoreConfigAsFloat(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_AMOUNT);

            $this->addTotalBefore (new Varien_Object(array(
                'code'       => Gamuza_Basic_Model_Quote_Address_Total_Waiter::CODE,
                'strong'     => true,
                'value'      => $value,
                'base_value' => $value,
                'label'      => $this->helper('basic')->__('Waiter Amount'),
                'area'       => 'footer',
            )), 'grand_total');
        }

        return $this;
    }
}

