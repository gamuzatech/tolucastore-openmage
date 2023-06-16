<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml customer grid block
 */
class Gamuza_Basic_Block_Adminhtml_Customer_Online_Grid
    extends Mage_Adminhtml_Block_Customer_Online_Grid
{
    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        $this->removeColumn('middlename');

        return $result;
    }
}

