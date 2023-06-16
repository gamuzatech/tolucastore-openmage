<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Widget_Grid_Column_Renderer_Longtext
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Longtext
{
    const ASTERISK_BOLDER_PATTERN = '#\*{1}(.*?)\*{1}#';

    public function render ($row)
    {
        $result = parent::render ($row);

        if ($this->getColumn ()->getBolder ())
        {
            $result = preg_replace (self::ASTERISK_BOLDER_PATTERN, '<b>$1</b>', $result);
        }

        return $result;
    }
}

