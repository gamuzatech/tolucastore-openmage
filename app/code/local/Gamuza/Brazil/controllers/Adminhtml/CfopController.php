<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Adminhtml_CfopController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/brazil/cfop');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/brazil/cfop')
            ->_addBreadcrumb(
                Mage::helper ('brazil')->__('CFOP Manager'),
                Mage::helper ('brazil')->__('CFOP Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Brazil'));
	    $this->_title ($this->__('CFOP Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

