<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
?>
<?php
class Magebees_Responsivebannerslider_Block_View extends Mage_Core_Block_Template
{
 
    public function __construct() 
    {
        $this->setLazylaod(Mage::getStoreConfig("responsivebannerslider/general/lazy_load_jquery"));
    }    
    public function getGroupscollection()
    { 
           $groups = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection();
        $groups ->addFieldToFilter('status', 1);
        $groups ->addFieldToFilter('slidergroup_id', $this->getCode());
        $cms_page = Mage::getStoreConfig('responsivebannerslider/general/cms_page');
        $category_page = Mage::getStoreConfig('responsivebannerslider/general/category_page');
        $product_page = Mage::getStoreConfig('responsivebannerslider/general/product_page');
        $enabled = Mage::getStoreConfig('responsivebannerslider/general/enabled');
        
        if($enabled == 0) {
            return false;
        }    
        
         if(Mage::registry('current_category')) {
            if($category_page == 0) {
                return false;
            }        
         }elseif (Mage::registry('current_product')) {
            if($product_page == 0) {
                return false;
            }    
         }elseif (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms') {
            if($cms_page == 0) {
                return false;
            }
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
