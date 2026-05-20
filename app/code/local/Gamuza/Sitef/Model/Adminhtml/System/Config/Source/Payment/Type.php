<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Type config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Payment_Type
{
    private $_groups = array ();

    private $_subgroups = array ();

    public function __construct()
    {
        $this->_groups = array(
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_BANK_CHECK   => Mage::helper ('sitef')->__('Bank Check'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_DEBIT_CARD   => Mage::helper ('sitef')->__('Debit Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_CREDIT_CARD  => Mage::helper ('sitef')->__('Credit Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_VOUCHER_CARD => Mage::helper ('sitef')->__('Voucher Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_LOYALTY_CARD => Mage::helper ('sitef')->__('Loyalty Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_CASH_MONEY   => Mage::helper ('sitef')->__('Cash Money'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_GROUP_OTHER_CARD   => Mage::helper ('sitef')->__('Other Card'),
        );

        $this->_subgroups = array(
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_CASH_DOWN          => Mage::helper ('sitef')->__('Cash Down'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_PRE_DATED          => Mage::helper ('sitef')->__('Predated'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_INSTALLMENT_STORE  => Mage::helper ('sitef')->__('Installment Store'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_INSTALLMENT_ISSUER => Mage::helper ('sitef')->__('Installment Issuer'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_CASH_DOWN_INTEREST => Mage::helper ('sitef')->__('Cash Down Interest'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_STORE_CREDIT       => Mage::helper ('sitef')->__('Store Credit'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_SUBGROUP_OTHER_TYPE         => Mage::helper ('sitef')->__('Other Type'),
        );
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array();

        foreach ($this->_groups as $gid => $group)
        {
            foreach ($this->_subgroups as $sgid => $subgroup)
            {
                $value = sprintf ('%s%s', $gid, $sgid);
                $label = sprintf ('%s - %s', $group, $subgroup);

                $result[] = array('value' => $value, 'label' => $label);                
            }
        }

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();

        foreach ($this->toOptionArray() as $option)
        {
            $result[$option['value']] = $option['label'];
        }

        return $result;
    }
}

