<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Region extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'brazil';
	    $this->_controller = 'adminhtml_region';

	    $this->_headerText = Mage::helper ('brazil')->__('Regions Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

