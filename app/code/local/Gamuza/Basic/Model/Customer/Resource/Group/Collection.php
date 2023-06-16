<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer group collection
 */
class Gamuza_Basic_Model_Customer_Resource_Group_Collection
    extends Mage_Customer_Model_Resource_Group_Collection
{
    public function toOptionArray()
    {
        return parent::_toOptionArray('customer_group_id', 'name');
    }

    public function toOptionHash()
    {
        return parent::_toOptionHash('customer_group_id', 'name');
    }
}

