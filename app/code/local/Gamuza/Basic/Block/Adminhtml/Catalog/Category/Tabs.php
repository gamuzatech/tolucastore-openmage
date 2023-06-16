<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Category tabs
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Category_Tabs
    extends Mage_Adminhtml_Block_Catalog_Category_Tabs
{
    const CATALOG_CATEGORY_DESIGN_GROUP_ID_5 = 'group_5';
    const CATALOG_CATEGORY_DESIGN_GROUP_ID_6 = 'group_6';

    /**
     * Prepare Layout Content
     *
     * @return Mage_Adminhtml_Block_Catalog_Category_Tabs
     */
    protected function _prepareLayout()
    {
        $result = parent::_prepareLayout();

        // $this->removeTab (self::CATALOG_CATEGORY_DESIGN_GROUP_ID_5);
        $this->removeTab (self::CATALOG_CATEGORY_DESIGN_GROUP_ID_6);

        return $result;
    }
}

