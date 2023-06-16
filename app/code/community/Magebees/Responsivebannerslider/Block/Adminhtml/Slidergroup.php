<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() 
    {    
        $this->_controller = 'adminhtml_slidergroup';
        $this->_blockGroup = 'responsivebannerslider';
        $this->_headerText = Mage::helper('responsivebannerslider')->__('Responsive Banner Slider Groups');
        $this->_addButtonLabel = Mage::helper('responsivebannerslider')->__('Add Group');
        parent::__construct();
    }
    
      protected function _prepareLayout() 
      {
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild(
                'store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
            );
        }

        return parent::_prepareLayout();
      }

    public function getGridHtml() 
    {
        return $this->getChildHtml('store_switcher') . $this->getChildHtml('grid');
    }
    
    public function getGroupData() 
    {
        $groups = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection()->setOrder('slidergroup_id', 'ASC');
        if(count($groups) > 0) {
            foreach($groups as $group) {
                $options[$group->getData('slidergroup_id')] = $group->getTitle();
            }

            return $options;
        }
        else{
            return false;
        }             
    }
}
