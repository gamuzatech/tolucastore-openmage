<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
?>
<?php

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tab_Product extends  Mage_Adminhtml_Block_Widget_Grid
{
  
    public function __construct() 
    {
        parent::__construct();
        $this->setTemplate('responsivebannerslider/category/edit/tab/product.phtml');
    }
    protected function getProductIds() 
    {
        $data = Mage::registry('slidergroup_data');
        $prd_model = Mage::getModel('responsivebannerslider/product')->getCollection()
            ->addFieldToFilter('slidergroup_id', array('eq' => $data->getData('slidergroup_id')));
        $_productList = array();
        foreach($prd_model as $prd_data){
            $_productList[] = $prd_data->getData('product_sku');
        }

        return is_array($_productList) ? $_productList : array();
    }
    public function getIdsString() 
    {
        return implode(', ', $this->getProductIds());
    } 
}
