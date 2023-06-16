<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 **************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slide_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('responsivebannerslider/slide')->getCollection();
        $groupId = (int) $this->getRequest()->getParam('group');
        if($groupId != 0) {
            $collection->addFieldToFilter('group_names', array(array('finset' => $groupId)));  
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function Groupsid() 
    {
        $groups = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection();
        foreach($groups as $group) {
            $options[$group->getData('slidergroup_id')] = $group->getTitle();
        }

        return $options;
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn(
            'slide_id', array(
            'header'    => Mage::helper('responsivebannerslider')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'slide_id',
            )
        );
        $this->addColumn(
            'titles', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Slide Title'),
            'align'     =>'left',
            'index'     => 'titles',
            )
        );
        $this->addColumn(
            'Group', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Group'),
            'align'     =>'left',
            'index'        => 'group_names',
            'renderer'  => 'Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Renderer_Groups',
            'filter' => false,          
            )
        );
        $this->addColumn(
            'sort_order', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Sort Order'),
            'align'     =>'left',
            'index'     => 'sort_order',
            'width'     => '80px',
            )
        );
        $this->addColumn(
            'statuss', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'statuss',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'    =>  Mage::helper('responsivebannerslider')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('responsivebannerslider')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            )
        );
        
      return parent::_prepareColumns();
    }

    protected function _prepareMassaction()   
    {
        $this->setMassactionIdField('slide_id');
        $this->getMassactionBlock()->setFormFieldName('responsivebannerslider_slide');
        $this->getMassactionBlock()->addItem(
            'delete', array(
             'label'    => Mage::helper('responsivebannerslider')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('responsivebannerslider')->__('Are you sure?')
            )
        );
        $statuses = Mage::getSingleton('responsivebannerslider/status')->getOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem(
            'status', array(
             'label'=> Mage::helper('responsivebannerslider')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'statuss',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('responsivebannerslider')->__('Status'),
                         'values' => $statuses
                     )
             )
            )
        );
        return $this;
    }

    public function getRowUrl($row) 
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    public function getGridUrl() 
    {
         return  $this->getUrl('*/*/grid', array('_current' => true));
    }
}
