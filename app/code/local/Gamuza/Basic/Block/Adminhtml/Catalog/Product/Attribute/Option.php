<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml catalog product attributes block
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Attribute_Option
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct ()
    {
        $this->_blockGroup = 'basic';
        $this->_controller = 'adminhtml_catalog_product_attribute_option';

        $this->_headerText = Mage::helper ('basic')->__('Manage Attribute Options');
        $this->_addButtonLabel = Mage::helper ('basic')->__('Add New Option');

        parent::__construct ();
    }
}

