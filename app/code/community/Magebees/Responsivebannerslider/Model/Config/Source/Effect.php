<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Effect
{
   public function toOptionArray()
   {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Data1')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Data2 ')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Data3 ')),
        );
   }
}
