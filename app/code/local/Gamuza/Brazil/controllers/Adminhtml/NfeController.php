<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Adminhtml_NfeController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('gamuza/brazil/nfe');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()
            ->_setActiveMenu ('gamuza/brazil/nfe')
            ->_addBreadcrumb(
                Mage::helper ('brazil')->__('NF-e Manager'),
                Mage::helper ('brazil')->__('NF-e Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Brazil'));
	    $this->_title ($this->__('NF-e Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    public function responseAction ()
    {
        $nfe = $this->_initNFe ();
        $nfe = $this->_initNFeType ($nfe, 'response');

        if ($nfe && $nfe->getId ())
        {
            $this->_title ($this->__('Brazil'));
            $this->_title ($this->__('NF-e Manager'));
            $this->_title ($this->__('NF-e Response Manager'));

            $this->_initAction ();

            $this->renderLayout ();
        }
    }

    public function eventAction ()
    {
        $nfe = $this->_initNFe ();
        $nfe = $this->_initNFeType ($nfe, 'event');

        if ($nfe && $nfe->getId ())
        {
            $this->_title ($this->__('Brazil'));
            $this->_title ($this->__('NF-e Manager'));
            $this->_title ($this->__('NF-e Event Manager'));

            $this->_initAction ();

            $this->renderLayout ();
        }
    }

    protected function _initNFe ()
    {
        $id = $this->getRequest ()->getParam ('nfe_id');

        $nfe = Mage::getModel ('brazil/nfe')->load ($id);

        if (!$nfe || !$nfe->getId ())
        {
            $this->_getSession ()->addError ($this->__('This NF-e no longer exists.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        return $nfe;
    }

    protected function _initNFeType ($nfe, $type)
    {
        $collection = Mage::getModel ("brazil/nfe_{$type}")->getCollection ()
            ->addFieldToFilter ('nfe_id', array ('eq' => $nfe->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_getSession ()->addError ($this->__("This NF-e has no {$type}."));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        Mage::register('brazil_nfe',  $nfe);
        Mage::register('current_nfe', $nfe);

        return $nfe;
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'nfes.csv';
        $grid     = $this->getLayout()->createBlock('brazil/adminhtml_nfe_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'nfes.xml';
        $grid       = $this->getLayout()->createBlock('brazil/adminhtml_nfe_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

