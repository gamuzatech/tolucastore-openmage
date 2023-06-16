<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Config_Source_Navigationstyle
{
    public function toOptionArray()  
    {
        return array(
            array('value' => 'angle', 'label'=>Mage::helper('adminhtml')->__('Angle')),
            array('value' => 'angle_small', 'label'=>Mage::helper('adminhtml')->__('Angle Small')),
            array('value' => 'angle_circle', 'label'=>Mage::helper('adminhtml')->__('Angle Circle')),
            array('value' => 'angle_square', 'label'=>Mage::helper('adminhtml')->__('Angle Square')),
            array('value' => 'arrow', 'label'=>Mage::helper('adminhtml')->__('Arrow')),
            array('value' => 'arrow_circle', 'label'=>Mage::helper('adminhtml')->__('Arrow Circle')),
            array('value' => 'caret', 'label'=>Mage::helper('adminhtml')->__('Caret')),
            array('value' => 'chevron', 'label'=>Mage::helper('adminhtml')->__('Chevron')),
            array('value' => 'chevron_smooth', 'label'=>Mage::helper('adminhtml')->__('Chevron Smooth')),
            array('value' => 'chevron_circle', 'label'=>Mage::helper('adminhtml')->__('Chevron Circle')),
            array('value' => 'chevron_square', 'label'=>Mage::helper('adminhtml')->__('Chevron Square')),
        );
    }
}