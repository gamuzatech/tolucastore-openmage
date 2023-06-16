<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column
    extends Mage_Adminhtml_Block_Widget_Grid_Column
{
    protected function _getRendererByType ()
    {
        $result = parent::_getRendererByType ();

        switch ($result)
        {
            case 'adminhtml/widget_grid_column_renderer_action':
            {
                $result = 'basic/adminhtml_widget_grid_column_renderer_action';

                break;
            }
            case 'adminhtml/widget_grid_column_renderer_massaction':
            {
                $result = 'basic/adminhtml_widget_grid_column_renderer_massaction';

                break;
            }
        }

        return $result;
    }
}

