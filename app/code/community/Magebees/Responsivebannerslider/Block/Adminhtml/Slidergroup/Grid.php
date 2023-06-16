<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() 
    {
        parent::__construct();
        $this->setId('slidergroupGrid');
        $this->setDefaultSort('slidergroup_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()    
    {
        $collection = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection();
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if($storeId){
            $collection->storeFilter($storeId);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'slidergroup_id', array(
            'header'    => Mage::helper('responsivebannerslider')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'slidergroup_id',
            )
        );
        $this->addColumn(
            'title', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Group Title'),
            'align'     =>'left',
            'index'     => 'title',
            )
        );
        $this->addColumn(
            'sort_order', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Sort Order'),
            'align'     =>'left',
            'index'     => 'sort_order',
            )
        );
        $this->addColumn(
            'status', array(
            'header'    => Mage::helper('responsivebannerslider')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
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
        $this->setMassactionIdField('slidergroup_id');
        $this->getMassactionBlock()->setFormFieldName('responsivebannerslider_group');
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
                         'name' => 'status',
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
