<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Shipping_Tablerate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'basic';
	    $this->_controller = 'adminhtml_shipping_tablerate';

	    $this->_headerText = Mage::helper ('basic')->__('Table Rates Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

