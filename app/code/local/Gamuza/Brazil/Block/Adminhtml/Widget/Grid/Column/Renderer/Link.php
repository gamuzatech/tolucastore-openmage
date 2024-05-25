<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Widget_Grid_Column_Renderer_Link
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function _getValue (Varien_Object $row)
    {
        $index = $this->getColumn ()->getIndex ();

        if (!$row->getData ($index))
        {
            return null;
        }

        $link = $row->getData ($index);

        $result = sprintf ("<a target='_blank' href='%s'>%s</a>", $link, $link);

        return $result;
    }
}

