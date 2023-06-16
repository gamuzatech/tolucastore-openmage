<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml customer groups edit form
 */
class Gamuza_Basic_Block_Adminhtml_Customer_Group_Edit_Form
    extends Mage_Adminhtml_Block_Customer_Group_Edit_Form
{
    /**
     * Prepare form for render
     */
    protected function _prepareLayout()
    {
        $result = parent::_prepareLayout();

        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');

        $name = $fieldset->addField(
            'name',
            'text',
            array(
                'name'  => 'name',
                'label' => Mage::helper('basic')->__('Group Name'),
                'title' => Mage::helper('basic')->__('Group Name'),
                'class' => 'required-entry',
                'required' => true,
            ),
            'customer_group_code',
        );

        $customerGroup = Mage::registry('current_group');

        if (Mage::getSingleton('adminhtml/session')->getCustomerGroupData()) {
            $form->addValues(Mage::getSingleton('adminhtml/session')->getCustomerGroupData());
            Mage::getSingleton('adminhtml/session')->setCustomerGroupData(null);
        } else {
            $form->addValues($customerGroup->getData());
        }

        return $result;
    }
}

