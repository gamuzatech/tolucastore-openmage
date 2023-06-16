<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column_Renderer_Total
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render (Varien_Object $row)
    {
        $content = parent::render ($row);
        
        return $this->helper ('core')->currency ($content);
    }
}

