<?php
/*
 * @package     Toluca_Banner
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Banner_Block_Adminhtml_Slidergroup_Edit_Tabs
    extends Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tabs
{
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->removeTab('code_section');

        return Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
    }
}

