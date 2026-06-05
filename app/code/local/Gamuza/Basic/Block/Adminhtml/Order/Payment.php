<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Order_Payment
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct ()
    {
        $this->_blockGroup = 'basic';
        $this->_controller = 'adminhtml_order_payment';

        $this->_headerText = Mage::helper ('basic')->__('Payments Manager');

        parent::__construct();

        $this->_removeButton ('add');
    }
}

