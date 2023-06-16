<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('responsivebannerslider')->__('Manage Slide'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'slide_section', array(
            'label'     => Mage::helper('responsivebannerslider')->__('General Information'),
            'title'     => Mage::helper('responsivebannerslider')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slider_edit_tab_form')->toHtml(),
            )
        );
       return parent::_beforeToHtml();
    }
}
