<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Adminhtml_TransactionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/picpay/transaction');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/picpay/transaction')
            ->_addBreadcrumb(
                Mage::helper ('picpay')->__('Transaction Manager'),
                Mage::helper ('picpay')->__('Transaction Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('PicPay'));
	    $this->_title ($this->__('Transactions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

