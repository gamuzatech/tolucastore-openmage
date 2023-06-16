<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog manage products block
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product
    extends Mage_Adminhtml_Block_Catalog_Product
{
    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setChild('store_switcher_category', $this->getLayout()->createBlock('basic/adminhtml_store_switcher_category', 'store.switcher.category'));

        return parent::_prepareLayout();
    }

    /**
     * Render switcher
     *
     * @return string
     */
    public function getStoreSwitcherCategoryHtml()
    {
        return $this->getChildHtml('store_switcher_category');
    }
}

