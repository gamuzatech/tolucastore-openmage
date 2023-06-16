<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Navigationarrow
{
    public function toOptionArray() 
    {
        return array(
            array('value' => 'inside', 'label'=>Mage::helper('adminhtml')->__('Inside slider on both sides')),
            array('value' => 'outside', 'label'=>Mage::helper('adminhtml')->__('Outside the slider on both sides')),
            array('value' => 'inside_left', 'label'=>Mage::helper('adminhtml')->__('Inside slider grouped left')),
            array('value' => 'inside_right', 'label'=>Mage::helper('adminhtml')->__('Inside slider grouped right')),
        );
    }
}