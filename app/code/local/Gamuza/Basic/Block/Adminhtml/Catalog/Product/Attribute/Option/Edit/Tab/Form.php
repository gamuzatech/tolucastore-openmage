<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Attribute_Option_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_entityTypeId = null;

    public function _construct ()
    {
        parent::_construct ();

        $this->_entityTypeId = Mage::getSingleton ('eav/config')->getEntityType (Mage_Catalog_Model_Product::ENTITY)->getId ();
    }

	protected function _prepareForm ()
	{
		$form = new Varien_Data_Form ();
		$this->setForm ($form);

		$fieldset = $form->addFieldset ('basic_form', array ('legend' => Mage::helper ('basic')->__('Attribute Option Information')));

		$fieldset->addField ('attribute_id', 'select', array(
	        'label'    => Mage::helper ('basic')->__('Attribute'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'attribute_id',
	        'required' => true,
            'options'  => $this->_getAttributeOptions (),
		));
/*
		$fieldset->addField ('store_id', 'select', array(
	        'label'    => Mage::helper ('basic')->__('Store'),
	        'class'    => 'required-entry validate-select',
	        'name'     => 'store_id',
	        'required' => true,
            'options'  => $this->_getStoreOptions (),
            'after_element_html' => '<p class="nm"><small>' . $this->__('Admin => All') . '</small></p>',
		));
*/
		$fieldset->addField ('value', 'text', array(
		    'label'    => Mage::helper ('basic')->__('Value'),
		    'class'    => 'required-entry',
		    'name'     => 'value',
		    'required' => true,
		));
		$fieldset->addField ('sort_order', 'text', array(
		    'label'    => Mage::helper ('basic')->__('Sort Order'),
		    'class'    => 'required-entry validate-number validate-zero-or-greater',
		    'name'     => 'sort_order',
		    'required' => true,
		));

		if (Mage::getSingleton ('adminhtml/session')->getAttributeOptionData ())
		{
			$form->setValues (Mage::getSingleton ('adminhtml/session')->getAttributeOptionData ());

			Mage::getSingleton ('adminhtml/session')->setAttributeOptionData (null);
		}
		else if (Mage::registry ('attribute_option_data'))
        {
		    $form->setValues (Mage::registry ('attribute_option_data')->getData ());
		}

		return parent::_prepareForm();
	}

    private function _getAttributeOptions ()
    {
        $collection = Mage::getModel ('eav/entity_attribute')->getCollection ()
            ->setEntityTypeFilter ($this->_entityTypeId)
            ->setFrontendInputTypeFilter ('select')
            ->addFieldToFilter ('backend_type', array ('eq' => 'int'))
            ->addFieldToFilter ('source_model', array (
                array ('null' => true),
                array ('eq'   => 'eav/entity_attribute_source_table'),
            ))
        ;

        $result = array ();

        foreach ($collection as $attribute)
        {
            $result [$attribute->getId ()] = sprintf ('%s ( %s )',  $this->__($attribute->getFrontendLabel ()), $attribute->getAttributeCode ());
        }

        return $result;
    }

    private function _getStoreOptions ()
    {
        return Mage::getResourceModel ('core/store_collection')->setLoadDefault (true)->toOptionHash ();
    }
}

