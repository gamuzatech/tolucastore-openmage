<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column_Renderer_Color
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render (Varien_Object $row)
    {
        $content = parent::render ($row);

        if (empty ($content)) return null;

        $color = 'transparent';

        switch ($content)
        {
            case Mage_Sales_Model_Order::STATE_NEW:        { $color = 'orange'; break; }
            case Mage_Sales_Model_Order::STATE_PROCESSING: { $color = 'yellow'; break; }
            case Mage_Sales_Model_Order::STATE_COMPLETE:   { $color = 'green';  break; }
            case Mage_Sales_Model_Order::STATE_CLOSED:     { $color = 'gray';   break; }
            case Mage_Sales_Model_Order::STATE_HOLDED:     { $color = 'black';  break; }
            case Mage_Sales_Model_Order::STATE_CANCELED:   { $color = 'red';    break; }
        }

$result = <<< RESULT
<center>
<div style="background-color: {$color}; border: 1px solid #aaa; border-radius: 50%; height: 25px; position: relative; top: 0px; width: 25px;"></div>
</center>
RESULT;

        return $result;
    }
}

