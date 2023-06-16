<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Slide extends Mage_Core_Model_Abstract
{
    public function _construct()  
    {
        parent::_construct();
        $this->_init('responsivebannerslider/slide');
    }
    public function validateData(Varien_Object $object)  
    {
        $result   = array();
        $fromDate = $toDate = null;
        if ($object->hasFromDate() && $object->hasToDate()) {
            $fromDate = $object->getFromDate();
            $toDate = $object->getToDate();
        }

        if ($fromDate && $toDate) {
            $fromDate = new Zend_Date($fromDate, Varien_Date::DATETIME_INTERNAL_FORMAT);
            $toDate = new Zend_Date($toDate, Varien_Date::DATETIME_INTERNAL_FORMAT);
            if ($fromDate->compare($toDate) === 1) {
                $result[] = Mage::helper('responsivebannerslider')->__('End Date must be greater than Start Date.');
            }
        }

        return !empty($result) ? $result : true;
    }
}
