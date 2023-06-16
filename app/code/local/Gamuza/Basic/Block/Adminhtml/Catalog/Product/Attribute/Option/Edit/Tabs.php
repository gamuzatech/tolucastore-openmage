<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Attribute_Option_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct ()
	{
		parent::__construct ();

		$this->setId ('attribute_option_tabs');
		$this->setDestElementId ('edit_form');
		$this->setTitle (Mage::helper ('basic')->__('Attribute Option Information'));
	}

	protected function _beforeToHtml ()
	{
		$this->addTab ('form_section', array(
		    'label'   => Mage::helper ('basic')->__('Attribute Option Information'),
		    'title'   => Mage::helper ('basic')->__('Attribute Option Information'),
		    'content' => $this->getLayout ()->createBlock ('basic/adminhtml_catalog_product_attribute_option_edit_tab_form')->toHtml (),
		));

		return parent::_beforeToHtml ();
	}
}

