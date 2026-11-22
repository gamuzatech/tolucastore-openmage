<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Widget_Grid_Column_Renderer_Total
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render (Varien_Object $row)
    {
        $content = parent::render ($row);

        $result = $this->helper ('core')->currency ($content);

        return $result;
    }
}

