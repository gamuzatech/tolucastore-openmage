<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
error_reporting(0);
class Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function Groupsid() 
    {
        $groups = Mage::getModel('responsivebannerslider/responsivebannerslider')->getCollection()->setOrder('slidergroup_id', 'ASC');

        foreach($groups as $group) {
            $data = array(
                'value' => $group->getData('slidergroup_id'),
                'label' => $group->getTitle());
            $options[] = $data;        
        }

        return $options;
    }
  
    public function _sliderAdd() 
    {
        if($this->getRequest()->getParam('id')) { 
            return true; 
        } else { 
            return false; 
        }
    }

    protected function _prepareForm() 
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_form', array('legend'=>Mage::helper('responsivebannerslider')->__('General information')));
         $group_name = $fieldset->addField(
             'group_names', 'multiselect', array(
             'label'     => Mage::helper('responsivebannerslider')->__('Group'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'group_names[]',
             'values'    => $this->Groupsid(),
             )
         );
        $title = $fieldset->addField(
            'titles', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'titles',
            )
        );
        $img_video = $fieldset->addField(
            'img_video', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Image or Video'),
            'name'      => 'img_video',
            'disabled'     => $this->_sliderAdd(),
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_video')->toOptionArray(),
            )
        );
        $img_hosting = $fieldset->addField(
            'img_hosting', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Use External Image Hosting'),
            'name'      => 'img_hosting',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        
        $video_id = $fieldset->addField(
            'video_id', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Video ID'),
            'name'      => 'video_id',
            'note' => 'enter the video id of your YouTube or Vimeo video (not the full link)',

            )
        );      
        $hosted_url = $fieldset->addField(
            'hosted_url', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Hosted Image URL'),
            'name'      => 'hosted_url',
            'note'        => "Ex - http://example.com/filename",
            )
        );
        $hosted_thumb = $fieldset->addField(
            'hosted_thumb', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Hosted Image Thumb URL'),
            'name'      => 'hosted_thumb',
            'note'    => 'you can use the same URL as above but for performance reasons it\'s better to upload a seperate small thumbnail of this image, the thumbnails are used in carousels',
            )
        );
        $filename = $fieldset->addField(
            'filename', 'image', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Image'),
            'required'  => false,
            'name'      => 'filename',
            
            )
        );
        $alt_text = $fieldset->addField(
            'alt_text', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('ALT Text'),
            'name'      => 'alt_text',
            )
        );
        $url = $fieldset->addField(
            'url', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('URL'),
            'name'      => 'url',
            )
        );
        $url_target = $fieldset->addField(
            'url_target', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('URL Target'),
            'name'      => 'url_target',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_urltarget')->toOptionArray(),
            )
        );
         $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
             array('tab_id' => $this->getTabId())
         );
        $wysiwygConfig["files_browser_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index');
        $wysiwygConfig["directives_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["directives_url_quoted"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["add_images"] = false;
        $wysiwygConfig["add_widgets"] = false;
        $wysiwygConfig["add_variables"] = false;
        $wysiwygConfig["widget_plugin_src"] = false;
        $wysiwygConfig->setData("plugins", array());
        $style = 'height:20em; width:50em;';
        $config = $wysiwygConfig;
            
        $description = $fieldset->addField(
            'description', 'editor', array(
            'label' => Mage::helper('responsivebannerslider')->__('Description'),
            'required' => false,
            'name' => 'description',
            'style' => $style,
            'wysiwyg' => true,
            'config' => $config,
            )
        );
        $date_enabled = $fieldset->addField(
            'date_enabled', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Use Date Range'),
            'name'      => 'date_enabled',
            'values'    => array(
                0 => Mage::helper('responsivebannerslider')->__('No'), 
                1 => Mage::helper('responsivebannerslider')->__('Yes'), 
             ),
            )
        );
        $note= $this->__('The current server time is').': '.$this->formatTime(now(), Mage_Core_Model_Locale::FORMAT_TYPE_SHORT, true);
        $current_timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $from_date = $fieldset->addField(
            'from_date', 'date', array(
            'name' => 'from_date',
            'label' => Mage::helper('responsivebannerslider')->__('From Date & Time'),
            'title' => Mage::helper('responsivebannerslider')->__('From Date & Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'class' => 'validate-date',
            'time' => true,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso
            )
        );
        $to_date = $fieldset->addField(
            'to_date', 'date', array(
            'name' => 'to_date',
            'label' => Mage::helper('responsivebannerslider')->__('To Date & Time'),
            'title' => Mage::helper('responsivebannerslider')->__('To Date & Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'class' => 'validate-date',
            'time' => true,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
            'note'      =>$note,
            )
        );
        $sort_order = $fieldset->addField(
            'sort_order', 'text', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Sort Order'),
            'class'     => 'validate-number',
            'required'  => false,
            'name'      => 'sort_order',
            )
        ); 
        $status = $fieldset->addField(
            'statuss', 'select', array(
            'label'     => Mage::helper('responsivebannerslider')->__('Status'),
            'name'      => 'statuss',
            'values'    => Mage::getSingleton('responsivebannerslider/config_source_status')->toOptionArray(),
            )
        ); 
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }  
    
        if (Mage::getSingleton('adminhtml/session')->getSliderData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSliderData());
            Mage::getSingleton('adminhtml/session')->setSliderData(null);
        } elseif (Mage::registry('slider_data')) {
            $dataimg = Mage::registry('slider_data')->getData();
            if(count($dataimg)>0) {
                $tmp = "responsivebannerslider/".$dataimg['filename'];
                unset($dataimg['filename']);
                $dataimg = array_merge($dataimg, array("filename"=>$tmp));
                if($dataimg['filename'] == "responsivebannerslider/"){
                    unset($dataimg['filename']);
                    array_merge($dataimg, array("filename"=>""));
                    $form->setValues($dataimg);
                }else {
                    $form->setValues($dataimg);
                }
            }
        }
        
        $id = $this->getRequest()->getParam('id');
        if($id == ''){
            $dataimg['statuss'] = '1';
            $form->setValues($dataimg);
            $this->setForm($form);
        }

        $this->setForm($form);
        $this->setChild(
            'form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($img_video->getHtmlId(), $img_video->getName())
            ->addFieldMap($img_hosting->getHtmlId(), $img_hosting->getName())
            ->addFieldMap($video_id->getHtmlId(), $video_id->getName())
            ->addFieldMap($hosted_url->getHtmlId(), $hosted_url->getName())
            ->addFieldMap($hosted_thumb->getHtmlId(), $hosted_thumb->getName())
            ->addFieldMap($filename->getHtmlId(), $filename->getName())
            ->addFieldMap($alt_text->getHtmlId(), $alt_text->getName())
            ->addFieldMap($url->getHtmlId(), $url->getName())
            ->addFieldMap($url_target->getHtmlId(), $url_target->getName())
            ->addFieldMap($description->getHtmlId(), $description->getName())
            ->addFieldMap($to_date->getHtmlId(), $to_date->getName())
            ->addFieldMap($from_date->getHtmlId(), $from_date->getName())
            ->addFieldMap($date_enabled->getHtmlId(), $date_enabled->getName())
            ->addFieldDependence(
                $to_date->getName(),
                $date_enabled->getName(),
                1
            )
            ->addFieldDependence(
                $from_date->getName(),
                $date_enabled->getName(),
                1
            )
            ->addFieldDependence(
                $video_id->getName(),
                $img_video->getName(),
                array('youtube','vimeo')
            )
            ->addFieldDependence(
                $img_hosting->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $hosted_url->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $hosted_thumb->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $filename->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $alt_text->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $url->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $url_target->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $description->getName(),
                $img_video->getName(),
                'image'
            )
            ->addFieldDependence(
                $hosted_url->getName(),
                $img_hosting->getName(),
                1
            )
            ->addFieldDependence(
                $hosted_thumb->getName(),
                $img_hosting->getName(),
                1
            )
            ->addFieldDependence(
                $filename->getName(),
                $img_hosting->getName(),
                0
            )
        );     
        return parent::_prepareForm();
    }
}
