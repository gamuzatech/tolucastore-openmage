<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Attribute_Option_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct ()
	{
		parent::__construct ();

		$this->_blockGroup = 'basic';
		$this->_controller = 'adminhtml_catalog_product_attribute_option';
		$this->_objectId   = 'entity_id';

		$this->_updateButton ('save',   'label', Mage::helper ('basic')->__('Save Option'));
		$this->_updateButton ('delete', 'label', Mage::helper ('basic')->__('Delete Option'));

		$this->_addButton ('saveandcontinue', array(
			'label'   => Mage::helper ('basic')->__('Save and Continue Edit'),
			'onclick' => 'saveAndContinueEdit ()',
			'class'   => 'save',
		), -100);

		$this->_formScripts [] = "
			function saveAndContinueEdit () {
				editForm.submit ($('edit_form').action + 'back/edit/');
			}
		";
	}

	public function getHeaderText ()
	{
        $attributeOption = Mage::registry ('attribute_option_data');

		if ($attributeOption && $attributeOption->getId ())
        {
		    return Mage::helper ('basic')->__("Edit Attribute Option '%s'", $this->htmlEscape ($attributeOption->getId ()));
		} 
		else
        {
		     return Mage::helper ('basic')->__('Add New Option');
		}
	}
}

