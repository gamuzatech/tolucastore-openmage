<?php
class Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm() 
    {
         $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('responsivebannerslider')->__('General information')));
       
        $title = $fieldset->addField(
            'title', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
            )
        );
         $position = $fieldset->addField(
             'position', 'select', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Position'),
             'name'      => 'position',
             'values'    => Mage::getSingleton('responsivebannerslider/config_source_position')->toOptionArray(),
             )
         );
          $sort_order = $fieldset->addField(
              'sort_order', 'text', array(
              'label'     => Mage::helper('responsivebannerslider')->__('Sort Order'),
              'class'     => 'validate-number',
              'required'  => false,
              'name'      => 'sort_order',
              'note' => 'set the sort order in case of multiple group on one page'
              )
          ); 
         $status = $fieldset->addField(
             'status', 'select', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Status'),
             'name'      => 'status',
             'values'    => Mage::getSingleton('responsivebannerslider/config_source_status')->toOptionArray(),
             )
         );
    
        if (!Mage::app()->isSingleStoreMode()) {
            $stores = $fieldset->addField(
                'store_id', 'multiselect', array(
                'name'        => 'store_id[]',
                'label'        => Mage::helper('responsivebannerslider')->__('Visible In'),
                'required'    => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                     ->getStoreValuesForForm(),
                    
                )
            );
        }else {
            $stores = $fieldset->addField(
                'store_id', 'hidden', array(
                'name'        => 'store_id[]',
                'value'        => Mage::app()->getStore(true)->getId()
                )
            );
        }

        $fieldset = $form->addFieldset('effect_form', array('legend'=>Mage::helper('responsivebannerslider')->__('Slider Effect')));
        $start_animation = $fieldset->addField(
            'start_animation', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Auto Start Animation'),
            'name'      => 'start_animation',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );  
        $loop_slider = $fieldset->addField(
            'loop_slider', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Loop Slider '),
            'name'      => 'loop_slider',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );    
        $pause_snavigation = $fieldset->addField(
            'pause_snavigation', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Pause Slider On Navigation'),
            'name'      => 'pause_snavigation',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        $pause_shover = $fieldset->addField(
            'pause_shover', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Pause Slider On Hover'),
            'name'      => 'pause_shover',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        $animation_type = $fieldset->addField(
            'animation_type', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Animation Type'),
            'name'      => 'animation_type',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_animationtype')->toOptionArray(),
            )
        );
        $animation_duration = $fieldset->addField(
            'animation_duration', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Animation Duration'),
            'class'     => 'required-entry validate-number',
            'required'  => true,
            'name'      => 'animation_duration',
            'note' => 'in milliseconds (default is 600)',
            )
        );
        $animation_direction = $fieldset->addField(
            'animation_direction', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Animation Direction'),
            'name'      => 'animation_direction',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_animationdirection')->toOptionArray(),
            )
        );
        $slide_duration = $fieldset->addField(
            'slide_duration', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Slide Duration'),
            'class'     => 'required-entry validate-number',
            'required'  => true,
            'name'      => 'slide_duration',
            'note' => 'in milliseconds (default is 7000)',
            )
        );
        $random_order = $fieldset->addField(
            'random_order', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Random Order'),
            'name'      => 'random_order',
              'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        $smooth_height = $fieldset->addField(
            'smooth_height', 'select', array(
              'label'     => Mage::helper('responsivebannerslider')->__('Smooth Height '),
              'name'      => 'smooth_height',
              'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
          $fieldset = $form->addFieldset('style_form', array('legend'=>Mage::helper('responsivebannerslider')->__('Slider Style')));
         $max_width = $fieldset->addField(
             'max_width', 'text', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Maximum Width Slider'),
             'class'     => 'validate-number',
             'required'  => false,
             'name'      => 'max_width',
             'note' => 'maximum width of the slider in pixels, leave empty or 0 for full responsive width',
             )
         );
         $slider_theme = $fieldset->addField(
             'slider_theme', 'select', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Slider Theme'),
             'name'      => 'slider_theme',
             'values'    => Mage::getSingleton('responsivebannerslider/config_source_theme')->toOptionArray(),
             )
         );
         $slider_type = $fieldset->addField(
             'slider_type', 'select', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Slider Type'),
             'name'      => 'slider_type',
             'values'    => Mage::getSingleton('responsivebannerslider/config_source_type')->toOptionArray(),
             )
         );
        $content_background = $fieldset->addField(
            'content_background', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Banner content background'),
            'required'  => false,
            'name'      => 'content_background',
            'class' => "color",
            )
        ); 
        $content_opacity = $fieldset->addField(
            'content_opacity', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Banner content opacity'),
            'class'     => 'validate-number',
            'required'  => false,
            'name'      => 'content_opacity',
            )
        );
        $thumbnail_size = $fieldset->addField(
            'thumbnail_size', 'text', array(
            'name'        => 'thumbnail_size',
            'label'        => Mage::helper('responsivebannerslider')->__('Thumbnail Width'),
            'required'    => false,
            'class'        => 'validate-number validate-greater-than-zero',
            'note' => 'width of the images in carousel, should not be larger then thumbnail upload width in general settings (default is 200)',
            )
        );
        $navigation_arrow = $fieldset->addField(
            'navigation_arrow', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Show Navigation Arrows'),
            'name'      => 'navigation_arrow',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_navigation')->toOptionArray(),
            )
        );
        $navigation_style = $fieldset->addField(
            'navigation_style', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Navigation Arrows Style'),
            'name'      => 'navigation_style',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_navigationstyle')->toOptionArray(),
            'onchange' => 'notEmpty()',
            'after_element_html' => '<td id="navi_arrow" class="scope-label"><i class="cws" id="navigation_style_name"></i></td>',
            )
        );
                  
        $navigation_aposition = $fieldset->addField(
            'navigation_aposition', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Navigation Arrows Position'),
            'name'      => 'navigation_aposition',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_navigationarrow')->toOptionArray(),
            )
        );
         $navigation_acolor = $fieldset->addField(
             'navigation_acolor', 'text', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Navigation Arrows Color'),
             'required'  => false,
             'name'      => 'navigation_acolor',
             'class' => 'color',
             )
         ); 
        $show_pagination = $fieldset->addField(
            'show_pagination', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Show Pagination'),
            'name'      => 'show_pagination',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_navigation')->toOptionArray(),
            )
        );
          $pagination_style = $fieldset->addField(
              'pagination_style', 'select', array(
              'label'     => Mage::helper('responsivebannerslider')->__('Pagination Style'),
              'name'      => 'pagination_style',
              'values'    => Mage::getSingleton('responsivebannerslider/config_source_paginationstyle')->toOptionArray(),
              )
          );
          $pagination_position = $fieldset->addField(
              'pagination_position', 'select', array(
              'label'     => Mage::helper('responsivebannerslider')->__('Pagination Position'),
              'name'      => 'pagination_position',
              'values'    => Mage::getSingleton('responsivebannerslider/config_source_paginationposition')->toOptionArray(),
              )
          );
        $pagination_color = $fieldset->addField(
            'pagination_color', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Pagination  Color'),
            'required'  => false,
            'name'      => 'pagination_color',
            'class' => 'color',
            )
        ); 
        $pagination_active = $fieldset->addField(
            'pagination_active', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Pagination Active Color'),
            'required'  => false,
            'name'      => 'pagination_active',
            'class' => 'color',
            )
        ); 
        $pagination_bar = $fieldset->addField(
            'pagination_bar', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Pagination Bar Background Color'),
            'required'  => false,
            'name'      => 'pagination_bar',
            'class' => 'color',
            )
        ); 
        
        if (Mage::getSingleton('adminhtml/session')->getSlidergroupData()) {
             $form->setValues(Mage::getSingleton('adminhtml/session')->getSlidergroupData());
            $data = Mage::getSingleton('adminhtml/session')->getSlidergroupData();
            Mage::getSingleton('adminhtml/session')->setSlidergroupData(null);
        }elseif (Mage::registry('slidergroup_data')) {
            $data = Mage::registry('slidergroup_data');
            $store_model = Mage::getModel('responsivebannerslider/store')->getCollection()->addFieldToFilter('slidergroup_id', array('eq' => $data->getData('slidergroup_id')));
            $store_data = array();
            foreach($store_model as $s_data){
                $store_data[] = $s_data->getData('store_id');
            }

            $model_data = $data->getData();
            array_push($model_data, $model_data['store_id'] = $store_data);
            $form->setValues($model_data);
        }

        $id = $this->getRequest()->getParam('id');
        if($id == ''){
            $model_data['animation_duration'] = '600';
            $model_data['slide_duration'] = '7000';
            $model_data['content_background'] = '333333';
            $model_data['content_opacity'] = '9';
            $model_data['navigation_acolor'] = '333333';
            $model_data['pagination_color'] = '777777';
            $model_data['pagination_active'] = '000000';
            $model_data['pagination_bar'] = 'e5e5e5';
            $model_data['thumbnail_size'] = '200';
            $form->setValues($model_data);
            $this->setForm($form);
        }

        $this->setChild(
            'form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($slider_type->getHtmlId(), $slider_type->getName())
            ->addFieldMap($thumbnail_size->getHtmlId(), $thumbnail_size->getName())
            ->addFieldMap($navigation_arrow->getHtmlId(), $navigation_arrow->getName())
            ->addFieldMap($navigation_style->getHtmlId(), $navigation_style->getName())
            ->addFieldMap($navigation_aposition->getHtmlId(), $navigation_aposition->getName())
            ->addFieldMap($navigation_acolor->getHtmlId(), $navigation_acolor->getName())
            ->addFieldMap($show_pagination->getHtmlId(), $show_pagination->getName())
            ->addFieldMap($pagination_style->getHtmlId(), $pagination_style->getName())
            ->addFieldMap($pagination_position->getHtmlId(), $pagination_position->getName())
            ->addFieldMap($pagination_color->getHtmlId(), $pagination_color->getName())
            ->addFieldMap($start_animation->getHtmlId(), $start_animation->getName())
            ->addFieldMap($loop_slider->getHtmlId(), $loop_slider->getName())
            ->addFieldMap($pause_snavigation->getHtmlId(), $pause_snavigation->getName())
            ->addFieldMap($pause_shover->getHtmlId(), $pause_shover->getName())
            ->addFieldMap($pagination_active->getHtmlId(), $pagination_active->getName())
            ->addFieldMap($pagination_bar->getHtmlId(), $pagination_bar->getName())
            ->addFieldDependence(
                $pagination_bar->getName(),
                $pagination_style->getName(),
                array('circular_bar','square_bar')
            )
            ->addFieldDependence(
                $thumbnail_size->getName(),
                $slider_type->getName(),
                array('carousel','bas-caro')
            )
            ->addFieldDependence(
                $navigation_style->getName(),
                $navigation_arrow->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $navigation_aposition->getName(),
                $navigation_arrow->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $navigation_acolor->getName(),
                $navigation_arrow->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $pagination_style->getName(),
                $show_pagination->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $pagination_position->getName(),
                $show_pagination->getName(),
                array('hover','always')
            )
            
            ->addFieldDependence(
                $pagination_active->getName(),
                $show_pagination->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $pagination_color->getName(),
                $show_pagination->getName(),
                array('hover','always')
            )
            ->addFieldDependence(
                $loop_slider->getName(),
                $start_animation->getName(),
                1
            )
            ->addFieldDependence(
                $pause_snavigation->getName(),
                $start_animation->getName(),
                1
            )
            ->addFieldDependence(
                $pause_shover->getName(),
                $start_animation->getName(),
                1
            )
        );
        return parent::_prepareForm();
    }
}
