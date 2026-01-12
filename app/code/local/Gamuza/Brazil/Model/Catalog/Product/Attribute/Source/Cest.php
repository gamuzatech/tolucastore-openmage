<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Cest
    extends Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
{
    public const AGREEMENT_ICMS_142_18 = 'Convênio ICMS 142/18';

    public function _getCollection ()
    {
        return Mage::getModel ('brazil/cest')->getCollection ()
            ->addFieldToFilter ('description', array ('nlike' => sprintf ('%%%s%%', self::AGREEMENT_ICMS_142_18)))
        ;
    }
}

