<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Operator extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'pdv';
	    $this->_controller = 'adminhtml_operator';

	    $this->_headerText = Mage::helper ('pdv')->__('Operators Manager');
        $this->_addButtonLabel = Mage::helper ('pdv')->__('Add New Operator');

	    parent::__construct();
	}
}

