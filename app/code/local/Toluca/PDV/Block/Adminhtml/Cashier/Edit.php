<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Cashier_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct ()
	{
		parent::__construct ();

		$this->_blockGroup = 'pdv';
		$this->_controller = 'adminhtml_cashier';
		$this->_objectId   = 'entity_id';

		$this->_updateButton ('save', 'label', Mage::helper ('pdv')->__('Save Cashier'));
        $this->_removeButton ('delete');

		$this->_addButton ('saveandcontinue', array(
			'label'   => Mage::helper ('pdv')->__('Save and Continue Edit'),
			'onclick' => 'saveAndContinueEdit ()',
			'class'   => 'save',
		), -100);

		$this->_formScripts [] = "
			function saveAndContinueEdit () {
				editForm.submit ($('edit_form').action + 'back/edit/');
			}
		";

        $cashier = Mage::registry ('cashier_data');

        if ($cashier && $cashier->getId () && $cashier->getStatus () == Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED)
        {
            $this->_removeButton ('save');
            $this->_removeButton ('saveandcontinue');
        }
	}

	public function getHeaderText ()
	{
        $cashier = Mage::registry ('cashier_data');

		if ($cashier && $cashier->getId ())
        {
		    return Mage::helper ('pdv')->__("Edit Cashier '%s'", $this->htmlEscape ($cashier->getId ()));
		}
		else
        {
		     return Mage::helper ('pdv')->__('Add New Cashier');
		}
	}
}

