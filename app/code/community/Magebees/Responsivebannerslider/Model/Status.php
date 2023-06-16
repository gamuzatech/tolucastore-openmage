<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Status extends Varien_Object
{
    const STATUS_DISABLED    = 0;
    const STATUS_ENABLED    = 1;
    static public function getOptionArray() 
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Enabled')),
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Disabled')),
        );
    }
}