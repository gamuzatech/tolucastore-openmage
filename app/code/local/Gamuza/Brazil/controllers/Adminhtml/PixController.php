<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Adminhtml_PixController extends Mage_Adminhtml_Controller_Action
{
    use Gamuza_Brazil_Trait_Controller_Pix;

    public $_publicActions = array ('qrcode');

    protected function _isAllowed ()
    {
        return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/brazil/pix');
    }
}

