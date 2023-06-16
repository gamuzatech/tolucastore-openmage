<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Mysql4_Responsivebannerslider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()   
    {
        parent::_construct();
        $this->_init('responsivebannerslider/responsivebannerslider');
    }
    public function categoryFilter($category) 
    {
        $this->getSelect()->join(
            array('category_table' => $this->getTable('responsivebannerslider/categories')),
            'main_table.slidergroup_id = category_table.slidergroup_id',
            array()
        )
                ->where('category_table.category_ids = ?', $category);
        return $this;
    }
    public function productFilter($productsku) 
    {
        $this->getSelect()->join(
            array('product_table' => $this->getTable('responsivebannerslider/product')),
            'main_table.slidergroup_id = product_table.slidergroup_id',
            array()
        )
                ->where('product_table.product_sku = ?', $productsku);
        return $this;
    }
    public function pageFilter($pageId) 
    {
        $this->getSelect()->join(
            array('page_table' => $this->getTable('responsivebannerslider/page')),
            'main_table.slidergroup_id = page_table.slidergroup_id',
            array()
        )
                ->where('page_table.pages = ?', $pageId);
        return $this;
    }
    public function storeFilter($store_id) 
    {
        $this->getSelect()->join(
            array('store_table' => $this->getTable('responsivebannerslider/store')),
            'main_table.slidergroup_id = store_table.slidergroup_id',
            array()
        )
                ->where('store_table. store_id = ?', $store_id);
        return $this;
    }
}
