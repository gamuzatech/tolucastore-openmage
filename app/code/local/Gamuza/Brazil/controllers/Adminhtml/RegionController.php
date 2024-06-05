<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Adminhtml_RegionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/brazil/region');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/brazil/region')
            ->_addBreadcrumb(
                Mage::helper ('brazil')->__('Regions Manager'),
                Mage::helper ('brazil')->__('Regions Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Brazil'));
	    $this->_title ($this->__('Regions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}
}

