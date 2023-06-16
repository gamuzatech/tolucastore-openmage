<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct() 
    {    
        $this->_init('responsivebannerslider/categories', 'category_id');
    }
}
