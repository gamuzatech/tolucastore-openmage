<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Adminhtml_Quote_DraftController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('sales/draft');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('sales/draft')
            ->_addBreadcrumb(
                Mage::helper ('basic')->__('Quote Drafts Manager'),
                Mage::helper ('basic')->__('Quote Drafts Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Sales'));
	    $this->_title ($this->__('Quote Drafts Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    /**
     * Export quote draft grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'quote_drafts.csv';
        $grid     = $this->getLayout()->createBlock('basic/adminhtml_quote_draft_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export quote draft grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'quote_drafts.xml';
        $grid       = $this->getLayout()->createBlock('basic/adminhtml_quote_draft_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

