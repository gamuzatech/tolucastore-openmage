<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Model_Observer
{
       
    public function adminSystemConfigChangedSection($observer)
    {
        // Resized Images //
    
        $dir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "responsivebannerslider" . DS . "thumbnails";
        if (is_dir($dir)) {
            foreach(glob($dir . '/*') as $file) {
                unlink($file); 
            }
        }

        $path_to_image_dir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "responsivebannerslider";
        if ($handle = opendir($path_to_image_dir))    {
            while (false !== ($file = readdir($handle))) {
                if (is_file($path_to_image_dir.'/'.$file)) {
                       $basepath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "responsivebannerslider" . DS . $file;
                    $newpath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "responsivebannerslider" . DS . "thumbnails". DS . $file;
                    $width =  Mage::getStoreConfig('responsivebannerslider/general/thumbnail_width');
                    if(trim($width) == "" || trim($width) < 0){
                        $width = "200";
                    }

                    if ($width != '') {
                        if (file_exists($basepath) && is_file($basepath) && !file_exists($newpath)) {
                            $imageObj = new Varien_Image($basepath);
                            $imageObj->constrainOnly(TRUE);
                            $imageObj->keepAspectRatio(FALSE);
                            $imageObj->keepFrame(FALSE);
                            $imageObj->resize($width);
                            $imageObj->save($newpath);
                        }
                    }
                }
            }

            closedir($handle);
        }
    }
}
