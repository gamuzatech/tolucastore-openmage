<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml permissions user grid
 */
class Gamuza_Basic_Block_Adminhtml_Api_User_Grid
    extends Mage_Adminhtml_Block_Api_User_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('api/user_collection')
            ->addFieldToFilter ('is_system', array ('neq' => true))
        ;

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}

