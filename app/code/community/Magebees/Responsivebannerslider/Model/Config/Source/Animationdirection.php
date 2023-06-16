<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Animationdirection
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'horizontal', 'label'=>Mage::helper('adminhtml')->__('Horizontal')),
            array('value' => 'vertical', 'label'=>Mage::helper('adminhtml')->__('Vertical')),
        );
    }
}