<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Mysql4_Nfe_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct ()
    {
        $this->_init ('brazil/nfe');
    }

    public function addOrderInfo ()
    {
        $this->getSelect ()
            ->joinLeft(
                array ('order' => Mage::getSingleton ('core/resource')->getTablename ('sales/order')),
                'main_table.order_id = order.entity_id',
                array(
                    'increment_id',
                    'protect_code',
                    'customer_id',
                    'customer_email',
                    'customer_firstname',
                    'customer_lastname',
                    'customer_taxvat',
                    'base_discount_amount',
                    'base_shipping_amount',
                    'base_shipping_discount_amount',
                    'payment_authorization_amount',
                    'is_pdv',
                    'pdv_cashier_id',
                    'pdv_operator_id',
                    'pdv_customer_id',
                    'pdv_history_id',
                    'pdv_sequence_id',
                    'pdv_table_id',
                )
            )
        ;

        return $this;
    }

    public function addIBGEInfo ()
    {
        $countryId = Mage::getStoreConfig (Mage_Core_Model_Locale::XML_PATH_DEFAULT_COUNTRY);

        $this->getSelect ()
            ->joinLeft(
                array ('country' => Mage::getSingleton ('core/resource')->getTablename ('brazil/country')),
                'main_table.country_id = country.code',
                array(
                    'country_name' => 'country.name',
                )
            )
            ->joinLeft(
                array ('region' => Mage::getSingleton ('core/resource')->getTablename ('brazil/region')),
                'main_table.region_id = region.code',
                array ()
            )
            ->joinLeft(
                array ('dcr' => Mage::getSingleton ('core/resource')->getTablename ('directory/country_region')),
                sprintf ("region.acronym = dcr.code AND dcr.country_id = '%s'", $countryId),
                array(
                    'region_code' => 'region.acronym',
                    'region_name' => 'dcr.default_name',
                )
           )
            ->joinLeft(
                array ('city' => Mage::getSingleton ('core/resource')->getTablename ('brazil/city')),
                'main_table.city_id = city.code',
                array (
                    'city_name' => 'city.name',
                )
            )
        ;

        return $this;
    }

    public function toOptionHash ($valueField = 'id', $labelField = 'name')
    {
        return $this->_toOptionHash ($valueField, $labelField);
    }
}

