<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Ncm
    extends Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
{
    public function _getCollection ()
    {
        $collection = Mage::getModel ('brazil/ibpt')->getCollection ()
            ->addFieldToFilter ('type', array ('eq' => Gamuza_Brazil_Helper_Data::IBPT_TYPE_NCM))
            ->addFieldToFilter ('exception', array ('null' => true))
        ;

        return $collection;
    }
}

