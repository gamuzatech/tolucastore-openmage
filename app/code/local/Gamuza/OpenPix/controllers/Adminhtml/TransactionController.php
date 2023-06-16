<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Adminhtml_TransactionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/openpix/transaction');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/openpix/transaction')
            ->_addBreadcrumb(
                Mage::helper ('openpix')->__('Transaction Manager'),
                Mage::helper ('openpix')->__('Transaction Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('OpenPix'));
	    $this->_title ($this->__('Transactions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

