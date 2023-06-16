<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies. (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product option model
 */
class Gamuza_Basic_Model_Catalog_Product_Option extends Mage_Catalog_Model_Product_Option
{
    /**
     * Save options.
     *
     * @return Mage_Catalog_Model_Product_Option
     */
    public function saveOptions ()
    {
        parent::saveOptions ();

        $write = Mage::getSingleton ('core/resource')->getConnection ('core_write');

        $collection = $this->getCollection ()
            ->addFieldToFilter ('product_id', $this->getProduct ()->getId ())
            ->addTitleToResult (Mage_Core_Model_App::ADMIN_STORE_ID)
        ;

        $collection->getSelect ()
            ->reset (Zend_Db_Select::COLUMNS)
            ->columns (array ('option_id', 'product_id'))
            ->order ('main_table.sort_order')
            ->order ('default_option_title.title')
        ;

        $order = 0;

        foreach ($collection as $option)
        {
            $query = sprintf ('UPDATE catalog_product_option SET sort_order = %s WHERE option_id = %s AND product_id = %s LIMIT 1',
                ++ $order, $option->getOptionId (), $option->getProductId ()
            );

            $write->query ($query);

            /**
             * Value
             */
            $valueCollection = Mage::getResourceModel ('catalog/product_option_value_collection')
                ->addFieldToFilter ('option_id', $option->getId ())
                ->addTitleToResult (Mage_Core_Model_App::ADMIN_STORE_ID)
            ;

            $valueCollection->getSelect ()
                ->reset (Zend_Db_Select::COLUMNS)
                ->columns (array ('option_type_id', 'option_id'))
                ->order ('main_table.sort_order')
                ->order ('default_value_title.title')
            ;

            $valueOrder = 0;

            foreach ($valueCollection as $value)
            {
                $query = sprintf ('UPDATE catalog_product_option_type_value SET sort_order = %s WHERE option_type_id = %s AND option_id = %s LIMIT 1',
                    ++ $valueOrder, $value->getOptionTypeId (), $value->getOptionId ()
                );

                $write->query ($query);
            }
        }

        return $this;
    }
}

