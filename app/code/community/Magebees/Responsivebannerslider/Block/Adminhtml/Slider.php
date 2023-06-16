<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() 
    {
        $this->_controller = 'adminhtml_slider';
        $this->_blockGroup = 'responsivebannerslider';
        $this->_headerText = Mage::helper('responsivebannerslider')->__('Responsive Banner Slider - Slides');
        $this->_addButtonLabel = Mage::helper('responsivebannerslider')->__('Add Slide');
        parent::__construct();
    }
}
