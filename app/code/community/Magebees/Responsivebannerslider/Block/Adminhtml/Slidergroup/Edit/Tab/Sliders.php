<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tab_Sliders extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() 
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slide_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }
    protected function _prepareCollection() 
    {
        $slide_collection = Mage::getModel('responsivebannerslider/slide')->getCollection();
        $current_groupid = $this->getGroupId();
        $slide_collection->addFieldToFilter('group_names', array(array('finset' => $current_groupid)));
        $this->setCollection($slide_collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns() 
    {
        $this->addColumn(
            'slide_id', array(
            'header'    => $this->__('ID'),
            'align'        => 'left',
            'width'        => '60px',
            'index'        => 'slide_id',
            'filter' => false,
            )
        );
         $this->addColumn(
             'titles', array(
             'header'    => $this->__('Title'),
             'align'        => 'left',
             'index'        => 'titles',
             'filter' => false,
             )
         );
        $this->addColumn(
            'statuss', array(
            'header'    => $this->__('Enabled'),
            'width'        => '90px',
            'index'        => 'statuss',
            'type'        => 'options',
            'options'    => array(
                1 => $this->__('Enabled'),
                0 => $this->__('Disabled'),
            ),
            'filter' => false,
            )
        );
         return parent::_prepareColumns();
    }
    public function getGroupId() 
    {
        return Mage::registry('slidergroup_data') ? Mage::registry('slidergroup_data')->getId() : 0;
    }
    public function getRowUrl($row) 
    {
        return $this->getUrl('adminhtml/slider/edit', array('id' => $row->getId()));
    }
    
}
