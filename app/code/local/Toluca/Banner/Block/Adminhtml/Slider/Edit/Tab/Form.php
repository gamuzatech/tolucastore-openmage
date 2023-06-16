<?php
/*
 * @package     Toluca_Banner
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Banner_Block_Adminhtml_Slider_Edit_Tab_Form
    extends Magebees_Responsivebannerslider_Block_Adminhtml_Slider_Edit_Tab_Form
{
    protected function _prepareForm() 
    {
        $result = parent::_prepareForm();

        $form = $this->getForm();
   
        $form->getElement('video_id')
            ->setNote(Mage::helper('banner')->__('enter the video id of your YouTube or Vimeo video (not the full link)'))
        ;

        $form->getElement('hosted_url')
            ->setNote(Mage::helper('banner')->__('Ex - http://example.com/filename'))
        ;

        $form->getElement('hosted_thumb')
            ->setNote(Mage::helper('banner')->__('you can use the same URL as above but for performance reasons it\'s better to upload a seperate small thumbnail of this image, the thumbnails are used in carousels'))
        ;

        return $result;
    }
}

