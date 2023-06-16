<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Operator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('operator_tabs');
		$this->setDestElementId ('edit_form');
		$this->setTitle (Mage::helper ('pdv')->__('Operator Information'));
	}

	protected function _beforeToHtml ()
	{
		$this->addTab ('form_section', array(
		    'label'   => Mage::helper ('pdv')->__('Operator Information'),
		    'title'   => Mage::helper ('pdv')->__('Operator Information'),
		    'content' => $this->getLayout ()->createBlock ('pdv/adminhtml_operator_edit_tab_form')->toHtml (),
		));

		return parent::_beforeToHtml ();
	}
}

