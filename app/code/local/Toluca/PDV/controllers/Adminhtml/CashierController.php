<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Adminhtml_CashierController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/pdv/cashier');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('pdv/cashier')
            ->_addBreadcrumb(
                Mage::helper ('pdv')->__('Cashiers Manager'),
                Mage::helper ('pdv')->__('Cashiers Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('PDV'));
	    $this->_title ($this->__('Cashiers Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

	public function newAction ()
	{
	    $this->_title ($this->__('PDV'));
	    $this->_title ($this->__('Cashiers Manager'));
	    $this->_title ($this->__('New Cashier'));

        $id = $this->getRequest ()->getParam ('id');

	    $model = Mage::getModel ('pdv/cashier')->load ($id);

	    $cashierData = Mage::getSingleton ('adminhtml/session')->getCashierData (true);

	    if (!empty ($cashierData))
        {
		    $model->setData ($cashierData);
	    }

	    Mage::register ('cashier_data', $model);

        $this->_initAction ();

	    $this->_addContent ($this->getLayout ()->createBlock ('pdv/adminhtml_cashier_edit'));
        $this->_addLeft ($this->getLayout ()->createBlock ('pdv/adminhtml_cashier_edit_tabs'));

	    $this->renderLayout ();
	}

	public function editAction ()
	{
	    $this->_title ($this->__('PDV'));
		$this->_title ($this->__('Cashier'));
	    $this->_title ($this->__('Edit Cashier'));

		$id = $this->getRequest()->getParam ('id');

		$model = Mage::getModel ('pdv/cashier')->load ($id);

		if ($model && $model->getId ())
        {
			Mage::register ('cashier_data', $model);

            $this->_initAction ();

			$this->_addContent ($this->getLayout ()->createBlock ('pdv/adminhtml_cashier_edit'));
            $this->_addLeft ($this->getLayout()->createBlock ('pdv/adminhtml_cashier_edit_tabs'));

			$this->renderLayout();
		}
		else
        {
			Mage::getSingleton ('adminhtml/session')->addError (Mage::helper ('pdv')->__('Cashier does not exist.'));

			$this->_redirect ('*/*/index');
		}
	}

	public function saveAction()
	{
		$postData = $this->getRequest ()->getPost ();

		if ($postData)
        {
			try
            {
                $id = $this->getRequest ()->getParam ('id');

				$model = Mage::getModel ('pdv/cashier')
				    ->addData ($postData)
                    ->setData ($id ? 'updated_at' : 'created_at', date ('c'))
				    ->setId ($id)
				    ->save ()
                ;

                $code = hash ('crc32b', $model->getId ());

                $model->setCode ($code)->save ();

				Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('pdv')->__('Cashier was successfully saved.'));
				Mage::getSingleton ('adminhtml/session')->setCashierData (false);

				if ($this->getRequest()->getParam ('back'))
                {
					$this->_redirect ('*/*/edit', array ('id' => $model->getId ()));

					return $this;
				}

				$this->_redirect ('*/*/index');

				return $this;
			}
			catch (Exception $e)
            {
				Mage::getSingleton ('adminhtml/session')->addError ($e->getMessage ());
				Mage::getSingleton ('adminhtml/session')->setCashierData ($this->getRequest ()->getPost ());

				$this->_redirect ('*/*/edit', array ('id' => $this->getRequest ()->getParam ('id')));

			    return $this;
			}
		}

		$this->_redirect ('*/*/index');
	}

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'cashiers.csv';
        $grid     = $this->getLayout()->createBlock('pdv/adminhtml_cashier_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'cashiers.xml';
        $grid       = $this->getLayout()->createBlock('pdv/adminhtml_cashier_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

