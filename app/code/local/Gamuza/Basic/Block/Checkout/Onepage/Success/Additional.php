<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Checkout_Onepage_Success_Additional extends Mage_Core_Block_Template
{
    protected function _construct ()
    {
        $this->setTemplate ('gamuza/basic/checkout/onepage/success/additional.phtml');
    }

    public function getOrder ()
    {
        return Mage::registry ('current_order');
    }
}

