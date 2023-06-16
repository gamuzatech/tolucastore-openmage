<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Video
{
    public function toOptionArray()  
    {
        return array(
            array('value' => 'image', 'label'=>Mage::helper('adminhtml')->__('Image')),
            array('value' => 'youtube', 'label'=>Mage::helper('adminhtml')->__('Youtube Video')),
            array('value' => 'vimeo', 'label'=>Mage::helper('adminhtml')->__('Vimeo Video')),
        );
    }
}