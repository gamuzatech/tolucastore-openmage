<?php
/*
 * @package     Toluca_Banner
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Banner_Block_Adminhtml_Slidergroup
    extends Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup
{
    public function getGridHtml() 
    {
        return $this->getChildHtml('grid');
    }
}

