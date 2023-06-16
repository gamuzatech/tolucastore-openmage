<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Block_Adminhtml_Transaction extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'pagcripto';
	    $this->_controller = 'adminhtml_transaction';

	    $this->_headerText     = Mage::helper ('pagcripto')->__('Transactions Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

