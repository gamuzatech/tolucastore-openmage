<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Adminhtml_Order_PaymentController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('sales/payment');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('sales/payment')
            ->_addBreadcrumb(
                Mage::helper ('basic')->__('Payments Manager'),
                Mage::helper ('basic')->__('Payments Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Sales'));
	    $this->_title ($this->__('Payments Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    /**
     * Export order payment grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'order_payments.csv';
        $grid     = $this->getLayout()->createBlock('basic/adminhtml_order_payment_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order payment grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'order_payments.xml';
        $grid       = $this->getLayout()->createBlock('basic/adminhtml_order_payment_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

