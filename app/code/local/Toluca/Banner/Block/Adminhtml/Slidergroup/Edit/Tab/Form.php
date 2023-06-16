<?php
/*
 * @package     Toluca_Banner
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Banner_Block_Adminhtml_Slidergroup_Edit_Tab_Form
    extends Magebees_Responsivebannerslider_Block_Adminhtml_Slidergroup_Edit_Tab_Form
{
    protected function _prepareForm() 
    {
        $result = parent::_prepareForm();

        $form = $this->getForm();

        $form->getElement('sort_order')
            ->setNote(Mage::helper('banner')->__('set the sort order in case of multiple group on one page'))
        ;

        $form->getElement('loop_slider')
            ->setLabel(Mage::helper('banner')->__('Loop Slider'))
        ;

        $form->getElement('animation_duration')
            ->setNote(Mage::helper('banner')->__('in milliseconds (default is 600)'))
        ;

        $form->getElement('slide_duration')
            ->setNote(Mage::helper('banner')->__('in milliseconds (default is 7000)'))
        ;

        $form->getElement('smooth_height')
            ->setLabel(Mage::helper('banner')->__('Smooth Height'))
        ;

        $form->getElement('max_width')
            ->setNote(Mage::helper('banner')->__('maximum width of the slider in pixels, leave empty or 0 for full responsive width'))
        ;

        $form->getElement('thumbnail_size')
            ->setNote(Mage::helper('banner')->__('width of the images in carousel, should not be larger then thumbnail upload width in general settings (default is 200)'))
        ;

        $form->getElement('pagination_color')
            ->setLabel(Mage::helper('banner')->__('Pagination Color'))
        ;



        return $result;
    }
}

