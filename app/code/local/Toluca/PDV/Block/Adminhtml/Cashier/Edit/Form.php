<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Cashier_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
        protected function _prepareForm ()
        {
            $form = new Varien_Data_Form (array(
                'id'      => 'edit_form',
                'action'  => $this->getUrl ('*/*/save', array ('id' => $this->getRequest ()->getParam ('id'))),
                'method'  => 'post',
                'enctype' => 'multipart/form-data',
            ));

            $form->setUseContainer (true);
            $this->setForm ($form);

            return parent::_prepareForm ();
    }
}

