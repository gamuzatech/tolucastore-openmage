<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle Price View Attribute Renderer
 */
class Gamuza_Basic_Model_Bundle_Product_Attribute_Source_Price_View
    extends Mage_Bundle_Model_Product_Attribute_Source_Price_View
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options))
        {
            $this->_options = array(
                array(
                    'label' => Mage::helper('bundle')->__('Price Average'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_PRICE_AVERAGE,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('Price Static'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_PRICE_STATIC,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('As High as One'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_HIGH_AS_ONE,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('As Low as One'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_LOW_AS_ONE,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('As High as'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_HIGH_AS,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('As Low as'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_LOW_AS,
                ),
                array(
                    'label' => Mage::helper('bundle')->__('Price Range'),
                    'value' => Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_PRICE_RANGE,
                ),
            );
        }

        return $this->_options;
    }
}

