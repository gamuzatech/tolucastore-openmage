<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Cfop
    extends Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
{
    public const MEI = 'MEI';

    public function _getCollection ()
    {
        $collection = Mage::getModel ('brazil/cfop')->getCollection ()
            ->addFieldToFilter ('description', array ('nlike' => sprintf ('%%%s%%', self::MEI)))
        ;

        return $collection;
    }
}

