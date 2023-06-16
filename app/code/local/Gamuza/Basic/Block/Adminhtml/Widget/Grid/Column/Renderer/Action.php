<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render (Varien_Object $row)
    {
        if (!$row->getId ()) return;
        
        $content = parent::render ($row);
        
        return $content;
    }

    protected function _toLinkHtml ($action, Varien_Object $row)
    {
        $_action = $action;
        
        if (!array_key_exists ('title', $_action)) $_action ['title'] = $_action ['caption'];
        
        return parent::_toLinkHtml ($_action, $row);
    }
}

