<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Adminhtml_Pinpad_TransactionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/sitef/pinpad_transaction');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/sitef/transaction')
            ->_addBreadcrumb(
                Mage::helper ('sitef')->__('Pinpad Transactions Manager'),
                Mage::helper ('sitef')->__('Pinpad Transactions Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Sitef'));
	    $this->_title ($this->__('Pinpad Transactions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

