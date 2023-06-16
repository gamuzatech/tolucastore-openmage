<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Contact extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct ()
	{
	    $this->_blockGroup = 'bot';
	    $this->_controller = 'adminhtml_contact';

	    $this->_headerText = Mage::helper ('bot')->__('Contacts Manager');

	    parent::__construct();

        $this->_removeButton ('add');
	}
}

