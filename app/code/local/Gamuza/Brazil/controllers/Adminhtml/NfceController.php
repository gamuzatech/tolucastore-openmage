<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Adminhtml_NfceController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/brazil/nfce');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/brazil/nfce')
            ->_addBreadcrumb(
                Mage::helper ('brazil')->__('NFC-e Manager'),
                Mage::helper ('brazil')->__('NFC-e Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Brazil'));
	    $this->_title ($this->__('NFC-e Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    public function responseAction ()
    {
        $nfce = $this->_initNFCe ();
        $nfce = $this->_initNFCeType ($nfce, 'response');

        if ($nfce && $nfce->getId ())
        {
            $this->_title ($this->__('Brazil'));
            $this->_title ($this->__('NFC-e Manager'));
            $this->_title ($this->__('NFC-e Response Manager'));

            $this->_initAction ();

            $this->renderLayout ();
        }
    }

    public function eventAction ()
    {
        $nfce = $this->_initNFCe ();
        $nfce = $this->_initNFCeType ($nfce, 'event');

        if ($nfce && $nfce->getId ())
        {
            $this->_title ($this->__('Brazil'));
            $this->_title ($this->__('NFC-e Manager'));
            $this->_title ($this->__('NFC-e Event Manager'));

            $this->_initAction ();

            $this->renderLayout ();
        }
    }

    protected function _initNFCe ()
    {
        $id = $this->getRequest ()->getParam ('nfce_id');

        $nfce = Mage::getModel ('brazil/nfce')->load ($id);

        if (!$nfce || !$nfce->getId ())
        {
            $this->_getSession ()->addError ($this->__('This NFC-e no longer exists.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        return $nfce;
    }

    protected function _initNFCeType ($nfce, $type)
    {
        $collection = Mage::getModel ("brazil/nfce_{$type}")->getCollection ()
            ->addFieldToFilter ('nfce_id', array ('eq' => $nfce->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_getSession ()->addError ($this->__("This NFC-e has no {$type}."));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        Mage::register('brazil_nfce',  $nfce);
        Mage::register('current_nfce', $nfce);

        return $nfce;
    }
}

