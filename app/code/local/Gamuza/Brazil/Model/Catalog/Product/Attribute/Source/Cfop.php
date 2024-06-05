<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Cfop
    extends Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
{
    public function _getCollection ()
    {
        return Mage::getModel ('brazil/cfop')->getCollection ();
    }
}

