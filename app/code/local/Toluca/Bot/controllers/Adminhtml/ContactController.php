<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Adminhtml_ContactController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/bot/contact');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('bot/contact')
            ->_addBreadcrumb(
                Mage::helper ('bot')->__('Contacts Manager'),
                Mage::helper ('bot')->__('Contacts Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Bot'));
	    $this->_title ($this->__('Contacts Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    public function massStatusAction ()
    {
        $contactIds = $this->getRequest()->getParam('contact');
        $status     = $this->getRequest()->getParam('status');

        try
        {
            foreach ($contactIds as $id)
            {
                Mage::getSingleton ('bot/contact')->load ($id)
                    ->setIsActive ($status)
                    ->save ()
                ;
            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($contactIds))
            );
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_getSession ()->addError ($e->getMessage ());
        }
        catch (Exception $e)
        {
            $this->_getSession ()
                ->addException ($e, $this->__('An error occurred while updating the contact(s) status.')
            );
        }

        $this->_redirect ('*/*/index');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'contacts.csv';
        $grid     = $this->getLayout()->createBlock('bot/adminhtml_contact_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'contacts.xml';
        $grid       = $this->getLayout()->createBlock('bot/adminhtml_contact_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

