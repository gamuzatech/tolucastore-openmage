<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'pdv';
	    $this->_controller = 'adminhtml_log';

	    $this->_headerText = Mage::helper ('pdv')->__('Logs Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

