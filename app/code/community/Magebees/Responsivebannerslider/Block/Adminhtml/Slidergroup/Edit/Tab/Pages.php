<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
?>
<?php

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tab_Pages extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::registry('slidergroup_data');    
        $page_model = Mage::getModel('responsivebannerslider/page')->getCollection()
            ->addFieldToFilter('slidergroup_id', array('eq' => $data->getData('slidergroup_id')));
        $page = array();
        foreach($page_model as $page_data){
            $page[] = $page_data->getData('pages');
        }
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('pages_form', array('legend'=>Mage::helper('responsivebannerslider')->__('Group Pages')));
        $fieldset->addField(
            'pages', 'multiselect', array(
            'name'        => 'pages[]',
            'label'        => Mage::helper('responsivebannerslider')->__('Visible In'),
            'required'    => false,
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_pages')->toOptionArray(),
            'value'        => $page,
            )
        );
         return parent::_prepareForm();
    }
}
