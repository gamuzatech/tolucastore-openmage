<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Promotion_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm ()
	{
		$form = new Varien_Data_Form ();
		$this->setForm ($form);

		$fieldset = $form->addFieldset ('bot_form', array ('legend' => Mage::helper ('bot')->__('Promotion Information')));

		$fieldset->addField ('type_id', 'select', array(
	        'label'    => Mage::helper ('bot')->__('Type'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'type_id',
	        'required' => true,
            'options'  => Mage::getModel ('bot/adminhtml_system_config_source_bot_type')->toArray (),
		));
		$fieldset->addField ('name', 'text', array(
		    'label'    => Mage::helper ('bot')->__('Name'),
		    'class'    => 'required-entry',
		    'name'     => 'name',
		    'required' => true,
		));
		$fieldset->addField ('filename', 'file', array(
		    'label'    => Mage::helper ('bot')->__('Filename'),
		    'class'    => 'required-entry',
		    'name'     => 'filename',
		    'required' => true,
            'after_element_html' => '<p class="nm"><small>' . $this->__('Only JPG, JPEG, PNG and PDF fiels are supported.') . '</small></p>',
		));
		$fieldset->addField ('message', 'textarea', array(
		    'label'    => Mage::helper ('bot')->__('Message'),
		    'class'    => 'required-entry',
		    'name'     => 'message',
		    'required' => true,
            'after_element_html' => '<p class="nm"><small>' . $this->__('Maximum of 255 characters.') . '</small></p>',
		));

		if (Mage::getSingleton ('adminhtml/session')->getPromotionData ())
		{
			$form->setValues (Mage::getSingleton ('adminhtml/session')->getPromotionData ());

			Mage::getSingleton ('adminhtml/session')->setPromotionData (null);
		}
		else if (Mage::registry ('promotion_data'))
        {
		    $form->setValues (Mage::registry ('promotion_data')->getData ());
		}

        $directory = Mage::getBaseDir ('media') . DS . 'bot' . DS . 'promotion';
        $filename = $form->getElement ('filename')->getValue ();

        if (is_file ($directory . DS . $filename))
        {
            $form->getElement ('filename')->setClass ('')->setRequired (false);
        }

		return parent::_prepareForm();
	}
}

