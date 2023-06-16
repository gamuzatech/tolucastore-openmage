<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Paginationstyle
{
    public function toOptionArray() 
    {
        return array(
            array('value' => 'circular', 'label'=>Mage::helper('adminhtml')->__('Circular')),
            array('value' => 'squared', 'label'=>Mage::helper('adminhtml')->__('Square')),
            array('value' => 'circular_bar', 'label'=>Mage::helper('adminhtml')->__('Circular with bar')),
            array('value' => 'square_bar', 'label'=>Mage::helper('adminhtml')->__('Square with bar')),
        );
    }
}