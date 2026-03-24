<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * SalesRule Validator Model
 *
 * Allows dispatching before and after events for each controller action
 */
class Gamuza_Basic_Model_SalesRule_Validator extends Mage_SalesRule_Model_Validator
{
    /**
     * Check if rule can be applied for specific address/quote/customer
     *
     * @param   Mage_SalesRule_Model_Rule $rule
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  bool
     */
    protected function _canProcessRule ($rule, $address)
    {
        if (!parent::_canProcessRule ($rule, $address))
        {
            return false;
        }

        $weekdayIds = $rule->getWeekdayIds ();

        if (empty ($weekdayIds))
        {
            return true;
        }

        if (!is_array ($weekdayIds))
        {
            $weekdayIds = explode (',', $weekdayIds);
        }

        $weekdayIds = array_map ('intval', $weekdayIds);

        $day = (int) Mage::getModel ('core/date')->date ('N');

        if (!in_array ($day, $weekdayIds, true))
        {
            return false;
        }

        return true;
    }
}

