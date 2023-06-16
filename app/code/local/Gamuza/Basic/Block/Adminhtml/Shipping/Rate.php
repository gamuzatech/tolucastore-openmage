<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Shipping_Rate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct ()
    {
        $this->_controller = 'adminhtml_shipping_rate';
        $this->_blockGroup = 'basic';
        $this->_headerText = Mage::helper ('basic')->__('Shipping Rates Manager');

        parent::__construct ();

        $this->_removeButton ('add');
    }
}

