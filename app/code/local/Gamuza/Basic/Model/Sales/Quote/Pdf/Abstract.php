<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Quote Draft PDF Abstract model
 */
class Gamuza_Basic_Model_Sales_Quote_Pdf_Abstract extends Mage_Sales_Model_Order_Pdf_Abstract
{
    public function getPdf()
    {
        // nothing
    }

    /**
     * Insert logo to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param null|string|bool|int|Mage_Core_Model_Store $store $store
     */
    protected function insertLogo (&$page, $store = null, $pixelWidth = null, $pixelHeight = null)
    {
        $this->y = $this->y ? $this->y : 815;

        $image = Mage::getStoreConfig('sales/identity/logo', $store);

        if ($image)
        {
            $image = Mage::getBaseDir('media') . '/sales/store/logo/' . $image;

            if (is_file($image))
            {
                $extension = exif_imagetype($image) == IMAGETYPE_PNG ? 'png' : 'jpeg';
                $filename = sprintf('%s.%s', tempnam(sys_get_temp_dir(), 'pdf-logo-'), $extension);

                copy($image, $filename);

                $image = Zend_Pdf_Image::imageWithPath($filename);

                unlink($filename);

                $top         = 830; //top border of the page
                $widthLimit  = 270; //half of the page width
                $heightLimit = 270; //assuming the image is not a "skyscraper"

                $width  = $pixelWidth  ?? $image->getPixelWidth();
                $height = $pixelHeight ?? $image->getPixelHeight();

                //preserving aspect ratio (proportions)
                $ratio = $width / $height;

                if ($ratio > 1 && $width > $widthLimit)
                {
                    $width  = $widthLimit;
                    $height = $width / $ratio;
                }
                elseif ($ratio < 1 && $height > $heightLimit)
                {
                    $height = $heightLimit;
                    $width  = $height * $ratio;
                }
                elseif ($ratio == 1 && $height > $heightLimit)
                {
                    $height = $heightLimit;
                    $width  = $widthLimit;
                }

                $y1 = $top - $height;
                $y2 = $top;
                $x1 = 25;
                $x2 = $x1 + $width;

                //coordinates after transformation are rounded by Zend
                $page->drawImage($image, $x1, $y1, $x2, $y2);

                $this->y = $y1 - 20;
            }
        }
    }

    public function newPage(array $settings = [])
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;

        if (!empty($settings['table_header']))
        {
            $this->_drawHeader($page);
        }

        return $page;
    }

    protected function _drawHeader(Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = [
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 35
        ];

        $lines[0][] = [
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 290,
            'align' => 'right'
        ];

        $lines[0][] = [
            'text'  => Mage::helper('sales')->__('Qty'),
            'feed'  => 435,
            'align' => 'right'
        ];

        $lines[0][] = [
            'text'  => Mage::helper('sales')->__('Price'),
            'feed'  => 360,
            'align' => 'right'
        ];

        $lines[0][] = [
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        ];

        $lines[0][] = [
            'text'  => Mage::helper('sales')->__('Subtotal'),
            'feed'  => 565,
            'align' => 'right'
        ];

        $lineBlock = [
            'lines'  => $lines,
            'height' => 5
        ];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }
}

