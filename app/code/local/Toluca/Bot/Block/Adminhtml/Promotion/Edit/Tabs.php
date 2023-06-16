<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Promotion_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('promotion_tabs');
		$this->setDestElementId ('edit_form');
		$this->setTitle (Mage::helper ('bot')->__('Promotion Information'));
	}

	protected function _beforeToHtml ()
	{
		$this->addTab ('form_section', array(
		    'label'   => Mage::helper ('bot')->__('Promotion Information'),
		    'title'   => Mage::helper ('bot')->__('Promotion Information'),
		    'content' => $this->getLayout ()->createBlock ('bot/adminhtml_promotion_edit_tab_form')->toHtml (),
		));

		return parent::_beforeToHtml ();
	}
}

