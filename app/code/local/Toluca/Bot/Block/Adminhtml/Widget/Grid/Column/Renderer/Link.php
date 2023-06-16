<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Widget_Grid_Column_Renderer_Link
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function _getValue (Varien_Object $row)
    {
        $index = $this->getColumn ()->getIndex ();

        if (!$row->getData ($index))
        {
            return null;
        }

        $mediaUrl = Mage::app ()->getStore (Mage_Core_Model_App::DISTRO_STORE_ID)->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_MEDIA, false);

        $mediaDir = $this->getColumn ()->getMedia ();

        $link = sprintf ("%s%s%s", $mediaUrl, $mediaDir, $row->getData ($index));

        $result = sprintf ("<a target='_blank' href='%s'>%s</a>", $link, $link);

        return $result;
    }
}

