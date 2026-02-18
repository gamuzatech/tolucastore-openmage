<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Quote_Draft
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct ()
    {
        $this->_blockGroup = 'basic';
        $this->_controller = 'adminhtml_quote_draft';

        $this->_headerText = Mage::helper ('basic')->__('Quote Drafts Manager');

        parent::__construct();

        $this->_removeButton ('add');
    }
}

