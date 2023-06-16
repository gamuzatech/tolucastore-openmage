<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Block_Adminhtml_Transaction extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'openpix';
	    $this->_controller = 'adminhtml_transaction';

	    $this->_headerText     = Mage::helper ('openpix')->__('Transactions Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

