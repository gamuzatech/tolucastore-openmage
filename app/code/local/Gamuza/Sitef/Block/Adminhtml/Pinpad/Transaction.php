<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Block_Adminhtml_Pinpad_Transaction extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'sitef';
	    $this->_controller = 'adminhtml_pinpad_transaction';

	    $this->_headerText     = Mage::helper ('sitef')->__('Pinpad Transactions Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

