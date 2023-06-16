<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product attributes tab
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    /**
     * Prepare attributes form
     *
     * @return null
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();

        $group = $this->getGroup();

        $fieldset = $this->getForm()->getElement('group_fields' . $group->getId());

        $fieldset->setHeaderBar(null);

        return $this;
    }
}

