<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
 
class Magebees_Responsivebannerslider_Model_Config_Source_Type
{
    public function toOptionArray()   
    {
        return array(
            array('value' => 'basic', 'label'=>Mage::helper('adminhtml')->__('Basic slider')),
            array('value' => 'carousel', 'label'=>Mage::helper('adminhtml')->__('Carousel')),
            array('value' => 'bas-caro', 'label'=>Mage::helper('adminhtml')->__('Basic slider with carousel navigation')),
       );
    }
}