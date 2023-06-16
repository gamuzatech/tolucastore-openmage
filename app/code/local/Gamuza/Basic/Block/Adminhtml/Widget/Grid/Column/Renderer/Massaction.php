<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column_Renderer_Massaction
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction
{
    public function render (Varien_Object $row)
    {
        $index = $this->getColumn ()->getIndex ();

        if (!$row->getData ($index))
        {
            return null;
        }

        $content = parent::render ($row);
        
        return $content;
    }
}

