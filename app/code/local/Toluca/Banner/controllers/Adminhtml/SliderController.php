<?php
/*
 * @package     Toluca_Banner
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Magebees_Responsivebannerslider') . DS . 'Adminhtml' . DS . 'SliderController.php');

class Toluca_Banner_Adminhtml_SliderController
    extends Magebees_Responsivebannerslider_Adminhtml_SliderController
{
    protected function _initAction ()
    {
        $result = parent::_initAction ();
        $result->_setActiveMenu ('toluca/responsivebannerslider/slides');

        return $result;
    }
}

