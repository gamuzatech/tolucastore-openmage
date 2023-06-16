<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Adminhtml_Shipping_RateController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed ()
    {
        return Mage::getSingleton ('admin/session')->isAllowed ('admin/shipping/rates');
    }

    protected function _initAction ()
    {
        $this->loadLayout ()->_setActiveMenu ('shipping/rates')
            ->_addBreadcrumb(
                Mage::helper ('adminhtml')->__('Shipping Rates Manager'),
                Mage::helper ('adminhtml')->__('Shipping Rates Manager')
            )
        ;

        return $this;
    }

    public function indexAction ()
    {
        $this->_title ($this->__('Shipping'));
        $this->_title ($this->__('Manage Shipping Rates'));

        $this->_initAction ();

        $this->renderLayout ();
    }

    /**
     * Export shipping rates grid to CSV format
     */
    public function exportCsvAction ()
    {
        $fileName   = 'shippingrates.csv';

        $content    = $this->getLayout ()
            ->createBlock ('basic/adminhtml_shipping_rate_grid')
            ->getCsvFile ()
        ;

        $this->_prepareDownloadResponse ($fileName, $content);
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'shippingrates.xml';
        $grid       = $this->getLayout()->createBlock('basic/adminhtml_shipping_rate_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

