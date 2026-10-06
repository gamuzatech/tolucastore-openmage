<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle selection renderer
 */
class Gamuza_Basic_Block_Bundle_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection
    extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection
{
    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml ()
    {
        $html = parent::_toHtml () . $this->getTemplateBoxHtml ();

        return $html;
    }

    public function getTemplateBoxHtml ()
    {
        $html = $this->getLayout ()
            ->createBlock ('adminhtml/template')
            ->setCanReadPrice ($this->getCanReadPrice ())
            ->setCanEditPrice ($this->getCanEditPrice ())
            ->setTemplate ('gamuza/basic/bundle/product/edit/bundle/option/selection/template/box.phtml')
            ->toHtml ()
        ;

        return $html;
    }
}

