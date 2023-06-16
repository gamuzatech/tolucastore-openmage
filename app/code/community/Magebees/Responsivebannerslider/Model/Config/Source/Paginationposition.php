<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Paginationposition
{
    public function toOptionArray()  
    {
        return array(
            array('value' => 'below', 'label'=>Mage::helper('adminhtml')->__('Below the slider')),
            array('value' => 'above', 'label'=>Mage::helper('adminhtml')->__('Above the slider')),
            array('value' => 'inside_top', 'label'=>Mage::helper('adminhtml')->__('Inside top slider')),
            array('value' => 'inside_bottom', 'label'=>Mage::helper('adminhtml')->__('Inside bottom slider')),
            array('value' => 'inside_bottom_left', 'label'=>Mage::helper('adminhtml')->__('Inside bottom left')),
            array('value' => 'inside_bottom_right', 'label'=>Mage::helper('adminhtml')->__('Inside bottom right')),
        );
    }
}