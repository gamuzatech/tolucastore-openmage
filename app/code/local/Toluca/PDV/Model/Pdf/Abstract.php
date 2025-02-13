<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * PDF Abstract model
 */
abstract class Toluca_PDV_Model_Pdf_Abstract extends Mage_Sales_Model_Order_Pdf_Abstract
{
    public $a4 = array ();

    public $total = 0;

    public function _construct ()
    {
        $this->a4 = explode (':', Zend_Pdf_Page::SIZE_A4);

        $this->x = $this->a4 [0]; // 595
        $this->y = $this->a4 [1]; // 842
    }

    /**
     * Retrieve PDF
     *
     * @return Zend_Pdf
     */
    public function getPdf ($grid = null)
    {
        if (!$grid instanceof Mage_Adminhtml_Block_Widget_Grid)
        {
            Mage::throwException (Mage::helper ('pdv')->__('Please define GRID object before using.'));
        }

        return $this->getPdfGrid ($grid);
    }

    protected function getPdfGrid ($grid)
    {
        $this->_beforeGetPdf ();

        $this->_initRenderer($this->_type);

        $pdf = new Zend_Pdf ();
        $this->_setPdf ($pdf);

        $style = new Zend_Pdf_Style ();
        $this->_setFontBold ($style, 12);

        $page = $this->newPage ();

        $this->insertLogo ($page);
        $this->insertAddress ($page);

        $allItems = $grid->getCollection ()->getItems ();
        $lastItem = $grid->getCollection ()->getLastItem ();

        foreach ($allItems as $item)
        {
            if ($item->getStoreId ())
            {
                Mage::app ()->getLocale ()->emulate ($item->getStoreId ());
                Mage::app ()->setCurrentStore ($item->getStoreId ());
            }

            /* Draw item */
            $this->_drawItemGrid ($item, $page, $grid);

            if ($item->getStoreId ())
            {
                Mage::app()->getLocale()->revert();
            }

            /* Calculate nextItem */
            if ($item != $lastItem)
            {
                $nextItem = next ($allItems);

                $nextHistory = Mage::getModel ('pdv/pdf_' . $this->_type);
                $nextHistory->_initRenderer ($this->_type);
                $nextHistory->_calcNextItemHeight ($nextItem, $grid);

                $y = $this->a4 [1] + 20;

                if ($y - $nextHistory->y > $this->y)
                {
                    $page = $this->newPage ();
                    $page = end ($pdf->pages);
                }
            }
        }

        $this->_afterGetPdf ();

        return $pdf;
    }

    /**
     * Draw Item process
     *
     * @return Zend_Pdf_Page
     */
    protected function _drawItemGrid (Varien_Object $item, Zend_Pdf_Page $page, Mage_Adminhtml_Block_Widget_Grid $grid)
    {
        $renderer = $this->_getRenderer ($this->_type);

        $this->_renderItemGrid ($item, $page, $grid, $renderer);

        $transportObject = new Varien_Object (['renderer_type_list' => array ()]);

        Mage::dispatchEvent ('pdf_item_draw_after', array(
            'transport_object' => $transportObject,
            'entity_item'      => $item,
        ));

        foreach ($transportObject->getRendererTypeList () as $type)
        {
            $renderer = $this->_getRenderer ($this->_type);

            if ($renderer)
            {
                $this->_renderItemGrid ($item, $page, $grid, $renderer);
            }
        }

        return $renderer->getPage ();
    }

    /**
     * Render item
     *
     * @return Toluca_PDV_Model_Pdf_Abstract
     */
    protected function _renderItemGrid (Varien_Object $item, Zend_Pdf_Page $page, Mage_Adminhtml_Block_Widget_Grid $grid, $renderer)
    {
        $renderer->setGrid ($grid)
            ->setItem ($item)
            ->setPdf ($this)
            ->setPage ($page)
            ->setRenderedModel ($this)
            ->draw ()
        ;

        return $this;
    }

    /**
     * Calculate next height
     *
     * @return int Height
     */
    protected function _calcNextItemHeight ($item, $grid)
    {
        $this->_setPdf (new Zend_Pdf ());

        $page = $this->_getPdf ()->newPage (Zend_Pdf_Page::SIZE_A4);

        $this->_drawItemGrid ($item, $page, $grid);

        return $this->y;
    }
}

