<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Adminhtml_Widget_Grid_Column_Renderer_Color
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render (Varien_Object $row)
    {
        $content = parent::render ($row);

        if (empty ($content)) return null;

        $color = 'transparent';

        switch ($content)
        {
            case Gamuza_Brazil_Helper_Data::NFE_STATUS_CREATED:    { $color = 'orange'; break; }
            case Gamuza_Brazil_Helper_Data::NFE_STATUS_SIGNED:     { $color = 'yellow'; break; }
            case Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED: { $color = 'green';  break; }
            case Gamuza_Brazil_Helper_Data::NFE_STATUS_DENIED:     { $color = 'gray';   break; }
            case Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED:   { $color = 'red';    break; }
        }

        $result = <<< RESULT
        <center>
        <div style="background-color: {$color}; border: 1px solid #aaa; border-radius: 50%; height: 25px; position: relative; top: 0px; width: 25px;"></div>
        </center>
        RESULT;

        return $result;
    }
}

