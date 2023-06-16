<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('slidergroup_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('responsivebannerslider')->__('Manage Groups'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_section', array(
            'label'     => Mage::helper('responsivebannerslider')->__('General Information'),
            'title'     => Mage::helper('responsivebannerslider')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_form')->toHtml(),
            )
        );
        $this->addTab(
            'pages_section', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Display on Pages'),
            'title'     => Mage::helper('responsivebannerslider')->__('Display on Pages'),
            'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_pages')->toHtml(),
            )
        );
        $this->addTab(
            'category_section', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Display on Categories'),
            'title'     => Mage::helper('responsivebannerslider')->__('Display on Categories'),
            'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_categories')->toHtml(),
            )
        );
        $this->addTab(
            'product_section', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Display on Product Pages'),
            'title'     => Mage::helper('responsivebannerslider')->__('Display on Product Pages'),
            'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_product')->toHtml(),
            )
        );
        if ($this->getRequest()->getParam('id')) {
            $this->addTab(
                'sliders_section', array(
                'label'     => Mage::helper('responsivebannerslider')->__('Slides of this Groups'),
                'title'     => Mage::helper('responsivebannerslider')->__('Slides of this Groups'),
                'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_sliders')->toHtml(),
                )
            );
            $this->addTab(
                'code_section', array(
                'label'     => Mage::helper('responsivebannerslider')->__('Use Code Inserts'),
                'title'     => Mage::helper('responsivebannerslider')->__('Use Code Inserts'),
                'content'   => $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_code')->toHtml(), 
                )
            );
        }
  
         return parent::_beforeToHtml();
    }
}
