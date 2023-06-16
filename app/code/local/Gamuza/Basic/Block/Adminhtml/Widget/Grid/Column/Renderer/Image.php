<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Adminhtml_Widget_Grid_Column_Renderer_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function _getValue (Varien_Object $row)
    {
        $index = $this->getColumn ()->getIndex ();

        if (!$row->getData ($index) || !strcmp ($row->getData ($index), 'no_selection'))
        {
            return null;
        }

        $mediaUrl = Mage::app ()->getStore (Mage_Core_Model_App::ADMIN_STORE_ID)->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_MEDIA, false);

        $mediaDir = $this->getColumn ()->getMedia ();

        $result = sprintf ("<img src='%s' width='75' />", sprintf ("%s%s%s", $mediaUrl, $mediaDir, $row->getData ($index)));

        return $result;
    }
}

