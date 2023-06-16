<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Theme
{
    public function toOptionArray()  
    {
        return array(
            array('value' => 'default', 'label'=>Mage::helper('adminhtml')->__('Default')),
            array('value' => 'blank', 'label'=>Mage::helper('adminhtml')->__('Blank')),
            array('value' => 'drop_shadow', 'label'=>Mage::helper('adminhtml')->__('Drop Shadow')),
            array('value' => 'embose', 'label'=>Mage::helper('adminhtml')->__('Embose')),
        );
    }
}