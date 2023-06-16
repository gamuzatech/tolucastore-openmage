<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Cashier_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    const FORMAT_TYPE_MEDIUM = Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM;

	protected function _prepareForm ()
	{
		$form = new Varien_Data_Form ();
		$this->setForm ($form);

		$fieldset = $form->addFieldset ('pdv_form', array ('legend' => Mage::helper ('pdv')->__('Cashier Information')));

		$fieldset->addField ('name', 'text', array(
		    'label'    => Mage::helper ('pdv')->__('Name'),
		    'class'    => 'required-entry',
		    'name'     => 'name',
		    'required' => true,
		));
		$fieldset->addField ('is_active', 'select', array(
	        'label'    => Mage::helper ('pdv')->__('Is Active'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'is_active',
	        'required' => true,
            'options'  => Mage::getModel ('adminhtml/system_config_source_yesno')->toArray (),
		));
		$fieldset->addField ('created_at', 'label', array(
	        'label'    => Mage::helper ('pdv')->__('Created At'),
	        'name'     => 'created_at',
		));
		$fieldset->addField ('updated_at', 'label', array(
	        'label'    => Mage::helper ('pdv')->__('Updated At'),
	        'name'     => 'updated_at',
		));

		if (Mage::getSingleton ('adminhtml/session')->getCashierData ())
		{
			$form->setValues (Mage::getSingleton ('adminhtml/session')->getCashierData ());

			Mage::getSingleton ('adminhtml/session')->setCashierData (null);
		}
		else if (Mage::registry ('cashier_data'))
        {
		    $form->setValues (Mage::registry ('cashier_data')->getData ());
		}

        $createdAt = $form->getElement ('created_at')->getValue ();

        if (!empty ($createdAt))
        {
            $form->getElement ('created_at')->setValue(
                Mage::helper ('core')->formatDate ($createdAt, self::FORMAT_TYPE_MEDIUM, true)
            );
        }

        $updatedAt = $form->getElement ('updated_at')->getValue ();

        if (!empty ($updatedAt))
        {
            $form->getElement ('updated_at')->setValue(
                Mage::helper ('core')->formatDate ($updatedAt, self::FORMAT_TYPE_MEDIUM, true)
            );
        }


		return parent::_prepareForm();
	}
}

