<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Operator_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm ()
	{
		$form = new Varien_Data_Form ();
		$this->setForm ($form);

		$fieldset = $form->addFieldset ('pdv_form', array ('legend' => Mage::helper ('pdv')->__('Operator Information')));

		$fieldset->addField ('cashier_id', 'select', array(
	        'label'    => Mage::helper ('pdv')->__('Cashier'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'cashier_id',
	        'required' => true,
            'options'  => Toluca_PDV_Block_Adminhtml_Operator_Grid::getCashiers (),
		));
		$fieldset->addField ('name', 'text', array(
		    'label'    => Mage::helper ('pdv')->__('Name'),
		    'class'    => 'required-entry',
		    'name'     => 'name',
		    'required' => true,
		));
		$fieldset->addField ('password', 'password', array(
	        'label'    => Mage::helper ('pdv')->__('Password'),
	        'class'    => 'required-entry',
	        'name'     => 'password',
	        'required' => true,
		));
		$fieldset->addField ('is_active', 'select', array(
	        'label'    => Mage::helper ('pdv')->__('Is Active'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'is_active',
	        'required' => true,
            'options'  => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));

		if (Mage::getSingleton ('adminhtml/session')->getOperatorData ())
		{
			$form->setValues (Mage::getSingleton ('adminhtml/session')->getOperatorData ());

			Mage::getSingleton ('adminhtml/session')->setOperatorData (null);
		}
		else if (Mage::registry ('operator_data'))
        {
		    $form->setValues (Mage::registry ('operator_data')->getData ());
		}

        $form->getElement ('password')->setValue ('');

		return parent::_prepareForm();
	}
}

