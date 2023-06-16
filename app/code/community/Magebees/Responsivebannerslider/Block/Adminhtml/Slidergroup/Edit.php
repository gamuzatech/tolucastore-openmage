<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()  
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'responsivebannerslider';
        $this->_controller = 'adminhtml_slidergroup';
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
			onload = function()
			{
				var e = document.getElementById('navigation_style');
				var strUser = e.options[e.selectedIndex].value;
				document.getElementById('navigation_style_name').addClassName(strUser);
			}			
			function notEmpty(){
				var e = document.getElementById('navigation_style');
				var strUser = e.options[e.selectedIndex].value;
				document.getElementById('navi_arrow').getElementsByTagName('i')[0].removeAttribute('class');
				document.getElementById('navigation_style_name').addClassName('cws '+strUser);				
			}		

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()  
    {
        if(Mage::registry('slidergroup_data') && Mage::registry('slidergroup_data')->getId()) {
            return Mage::helper('responsivebannerslider')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('slidergroup_data')->getTitle()));
        } else {
            return Mage::helper('responsivebannerslider')->__('Add Group');
        }
    }
}
