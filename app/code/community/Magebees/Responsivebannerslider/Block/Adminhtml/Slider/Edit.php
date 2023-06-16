<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
   
    public function __construct() 
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'responsivebannerslider';
        $this->_controller = 'adminhtml_slider';
        $this->_updateButton('save', 'label', Mage::helper('responsivebannerslider')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('responsivebannerslider')->__('Delete Item'));
        $this->_addButton(
            'saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
            ), -100
        );
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('web_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'web_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'web_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    protected function _prepareLayout() 
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }
    
    public function getHeaderText() 
    {
        if(Mage::registry('slider_data') && Mage::registry('slider_data')->getId()) {
            return Mage::helper('responsivebannerslider')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('slider_data')->getTitles()));
        } else {
            return Mage::helper('responsivebannerslider')->__('Add Slide');
        }
    }
}
