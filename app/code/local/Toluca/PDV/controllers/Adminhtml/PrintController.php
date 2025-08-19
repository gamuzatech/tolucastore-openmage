<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Adminhtml_PrintController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/pdv/print');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('toluca/pdv/print')
            ->_addBreadcrumb(
                Mage::helper ('pdv')->__('Prints Manager'),
                Mage::helper ('pdv')->__('Prints Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('PDV'));
	    $this->_title ($this->__('Prints Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'prints.csv';
        $grid     = $this->getLayout()->createBlock('pdv/adminhtml_print_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'prints.xml';
        $grid       = $this->getLayout()->createBlock('pdv/adminhtml_print_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

