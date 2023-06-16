<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Adminhtml_TransactionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/pagcripto/transaction');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/pagcripto/transaction')
            ->_addBreadcrumb(
                Mage::helper ('pagcripto')->__('Transaction Manager'),
                Mage::helper ('pagcripto')->__('Transaction Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('PagCripto'));
	    $this->_title ($this->__('Transactions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

