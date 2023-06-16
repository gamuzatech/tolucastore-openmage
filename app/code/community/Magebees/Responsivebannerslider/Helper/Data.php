<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function resizeImg($fileName) 
    {
        $dir = "thumbnails";
        $width = Mage::getStoreConfig('responsivebannerslider/general/thumbnail_width');
        if(trim($width) == "" || trim($width) < 0){
            $width = "200";
        }

        $basePath = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . $fileName;
        $newPath = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . $dir . DS . $fileName;
        if ($width != '') {
            if (file_exists($newPath)) {
                unlink($newPath);
            }

            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(TRUE);
                $imageObj->keepFrame(FALSE);
                $imageObj->quality(100);
                $imageObj->resize($width);
                $imageObj->save($newPath);
            }
        }
 
        return true;
    }
}
