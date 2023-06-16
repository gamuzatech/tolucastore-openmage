<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/
?>
<?php
class Magebees_Responsivebannerslider_Block_Managejquery extends Mage_Core_Block_Template
{
    
    public function addJquery()    
    {
        $disable_output = Mage::getStoreConfig('advanced/modules_disable_output/Magebees_Responsivebannerslider');
        if($disable_output == 0) {
            $enabled = Mage::getStoreConfig('responsivebannerslider/general/enabled');
            if($enabled) {
                $jquery_enabled = Mage::getStoreConfig('responsivebannerslider/general/jquery');
                $lazy_load_jquery = Mage::getStoreConfig('responsivebannerslider/general/lazy_load_jquery');
                $_head = $this->__getHeadBlock();
                if($jquery_enabled){
                    $_head->addJs('responsivebannerslider/jquery.min.js');
                }

                $_head->addJs('responsivebannerslider/noconflict.js');
                $_head->addJs('responsivebannerslider/jquery.flexslider.js');
                $_head->addJs('responsivebannerslider/jquery.easing.js');
                $_head->addJs('responsivebannerslider/froogaloop.js');
                $_head->addJs('responsivebannerslider/jquery.fitvid.js');
                if($lazy_load_jquery){
                    $_head->addJs('responsivebannerslider/jquery.lazy.js');
                }

                $_head->addCss('css/responsivebannerslider/default.css');
                
                return $_head;
            }    
        }    
    }
    private function __getHeadBlock() 
    {
        return $this->getLayout()->getBlock('head');
    }
}
