<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Position
{
    public function toOptionArray() 
    {
        return array(
            array('value' => 'content_top', 'label'=>Mage::helper('adminhtml')->__('Content Top')),
            array('value' => 'content_bottom', 'label'=>Mage::helper('adminhtml')->__('Content Bottom')),
        );
    }
}