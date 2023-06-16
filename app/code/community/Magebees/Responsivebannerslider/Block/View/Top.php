<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
?>
<?php
class Magebees_Responsivebannerslider_Block_View_Top extends Mage_Core_Block_Template
{
 

    public function __construct() 
    {
        $this->setLazylaod(Mage::getStoreConfig("responsivebannerslider/general/lazy_load_jquery"));
    }    

    public function getGroupscollection()
    { 
           $groups = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection();
        $groups ->addFieldToFilter('status', 1);
        $groups ->addFieldToFilter('position', 'content_top');
        $groups ->setOrder('sort_order', 'ASC');
        $cms_page = Mage::getStoreConfig('responsivebannerslider/general/cms_page');
        $category_page = Mage::getStoreConfig('responsivebannerslider/general/category_page');
        $product_page = Mage::getStoreConfig('responsivebannerslider/general/product_page');
        $current_page = Mage::app()->getFrontController()->getRequest()->getControllerName();
        
        if($current_page == "category") {
            if($category_page) {    
                $category_id = Mage::registry('current_category')->getId();    
                $groups->categoryFilter($category_id);
            }else{
                return false;
            }    
        }elseif ($current_page == "product") {
            if($product_page) {
                $productsku = Mage::registry('current_product')->getSku();
                $groups->productFilter($productsku);
            }else{
                return false;
            }    
        }elseif (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms') {
            if($cms_page) {
                $pageId = Mage::getBlockSingleton('cms/page')->getPage()->getPageId();
                $groups->pageFilter($pageId);
            }else{
                return false;
            }
        }

        if(Mage::app()->getFrontController()->getRequest()->getRouteName() == 'checkout') {
            return false;
        }
    
        $store_id = Mage::app()->getStore()->getId();
        if (!Mage::app()->isSingleStoreMode()) {
            $groups->storeFilter($store_id);
        }
 
        return $groups;
    }
    
    public function getSlides($slidegroupId) 
    {        
        $slide_collection = Mage::getModel('responsivebannerslider/slide')->getCollection()
            ->addFieldToFilter('group_names', array(array('finset' => $slidegroupId)))
            ->addFieldToFilter('statuss', '1')
            ->setOrder('sort_order', 'ASC');
        return $slide_collection;
    }
}
