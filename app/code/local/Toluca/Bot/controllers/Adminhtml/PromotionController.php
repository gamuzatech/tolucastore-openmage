<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('lib', 'Toluca_Bot') . DS . 'lib' . DS . 'Gamuza' . DS . 'File' . DS . 'Uploader.php');

class Toluca_Bot_Adminhtml_PromotionController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/bot/promotion');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('bot/promotion')
            ->_addBreadcrumb(
                Mage::helper ('bot')->__('Promotions Manager'),
                Mage::helper ('bot')->__('Promotions Manager')
            )
        ;

		return $this;
	}

	public function indexAction ()
	{
	    $this->_title ($this->__('Bot'));
	    $this->_title ($this->__('Promotions Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

	public function newAction ()
	{
	    $this->_title ($this->__('Bot'));
	    $this->_title ($this->__('Promotions Manager'));
	    $this->_title ($this->__('New Promotion'));

        $id = $this->getRequest ()->getParam ('id');

	    $model = Mage::getModel ('bot/promotion')->load ($id);

	    $promotionData = Mage::getSingleton ('adminhtml/session')->getPromotionData (true);

	    if (!empty ($promotionData))
        {
		    $model->setData ($promotionData);
	    }

	    Mage::register ('promotion_data', $model);

        $this->_initAction ();

	    $this->_addContent ($this->getLayout ()->createBlock ('bot/adminhtml_promotion_edit'));
        $this->_addLeft ($this->getLayout ()->createBlock ('bot/adminhtml_promotion_edit_tabs'));

	    $this->renderLayout ();
	}

	public function editAction ()
	{
	    $this->_title ($this->__('Bot'));
		$this->_title ($this->__('Promotion'));
	    $this->_title ($this->__('Edit Promotion'));

		$id = $this->getRequest()->getParam ('id');

		$model = Mage::getModel ('bot/promotion')->load ($id);

		if ($model && $model->getId ())
        {
			Mage::register ('promotion_data', $model);

            $this->_initAction ();

			$this->_addContent ($this->getLayout ()->createBlock ('bot/adminhtml_promotion_edit'));
            $this->_addLeft ($this->getLayout()->createBlock ('bot/adminhtml_promotion_edit_tabs'));

			$this->renderLayout();
		}
		else
        {
			Mage::getSingleton ('adminhtml/session')->addError (Mage::helper ('bot')->__('Promotion does not exist.'));

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

				$model = Mage::getModel ('bot/promotion')
				    ->addData ($postData)
                    ->setData ($id ? 'updated_at' : 'created_at', date ('c'))
				    ->setId ($id)
				    ->save ()
                ;

                if (!empty ($_FILES ['filename']) && is_uploaded_file ($_FILES ['filename']['tmp_name']))
                {
                    $promotionDir = Mage::getBaseDir ('media') . DS . 'bot' . DS . 'promotion';

                    $image = new Gamuza_File_Uploader ('filename');

                    $image->setAllowedExtensions (array('jpg', 'jpeg', 'png', 'pdf'))
                        ->setAllowCreateFolders (true)
                        ->setAllowRenameFiles (true)
                        ->setFilesDispersion (true)
                        ->save ($promotionDir)
                    ;

                    $model->setFilename ($image->getUploadedFilename ())->save ();
                }

				Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('bot')->__('Promotion was successfully saved.'));
				Mage::getSingleton ('adminhtml/session')->setPromotionData (false);

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
				Mage::getSingleton ('adminhtml/session')->setPromotionData ($this->getRequest ()->getPost ());

				$this->_redirect ('*/*/edit', array ('id' => $this->getRequest ()->getParam ('id')));

			    return $this;
			}
		}

		$this->_redirect ('*/*/index');
	}

	public function deleteAction ()
	{
        $id = $this->getRequest ()->getParam ('id');

		if ($id > 0)
        {
			try
            {
				$model = Mage::getModel ('bot/promotion');
			    $model->setId ($id)->delete ();

				Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('bot')->__('Promotion was successfully deleted.'));

				$this->_redirect ('*/*/index');

			    return $this;
			}
			catch (Exception $e)
            {
				Mage::getSingleton ('adminhtml/session')->addError ($e->getMessage ());

				$this->_redirect ('*/*/edit', array ('id' => $this->getRequest ()->getParam ('id')));

			    return $this;
			}
		}

		$this->_redirect ('*/*/index');
	}

    public function queueAction ()
    {
        $id = $this->getRequest ()->getParam ('id');

		if ($id > 0)
        {
			try
            {
                $collection = Mage::getModel ('bot/queue')->getCollection ()
                    ->addFieldToFilter ('promotion_id', array ('eq' => $id))
                    ->addFieldToFilter ('status', array ('in' => array (
                        Toluca_Bot_Helper_Data::QUEUE_STATUS_PENDING,
                        Toluca_Bot_Helper_Data::QUEUE_STATUS_SENDING,
                    )))
                ;

                if ($collection->getSize () > 0)
                {
                    throw new Exception (Mage::helper ('bot')->__('There are pending or sending promotions in the queue.'));
                }

                $promotion = Mage::getModel ('bot/promotion')->load ($id);

                $queue = Mage::getModel ('bot/queue')
                    ->setPromotionId ($promotion->getId ())
                    ->setTypeId ($promotion->getTypeId ())
                    ->setContactsTotal (0)
                    ->setContactsSent (0)
                    ->setStatus (Toluca_Bot_Helper_Data::QUEUE_STATUS_PENDING)
                    ->setName ($promotion->getName ())
                    ->setFilename ($promotion->getFilename ())
                    ->setMessage ($promotion->getMessage ())
                    ->setStartedAt (date ('c'))
                    ->setCreatedAt (date ('c'))
                    ->save ()
                ;

                $collection = Mage::getModel ('bot/contact')->getCollection ()
                    ->addFieldToFilter ('type_id',   array ('eq' => $queue->getTypeId ()))
                    ->addFieldToFilter ('is_active', array ('eq' => '1'))
                ;

                $collection->getSelect ()
                    ->reset (Zend_Db_Select::COLUMNS)
                    ->columns (array (
                        'entity_id'
                    ))
                ;

                if (!$collection->getSize ())
                {
                    $queue->delete ();

                    throw new Exception (Mage::helper ('bot')->__('There are no contacts available to send promotion.'));
                }

                $queue->setContactsTotal ($collection->getSize ())->save ();

                Mage::getSingleton ('core/resource_iterator')->walk ($collection->getSelect (), array (function ($args) {
                    $entityId = $args ['row']['entity_id'];

                    $promotion = $args ['promotion'];
                    $queue     = $args ['queue'];

                    Mage::getModel ('bot/log')
                        ->setPromotionId ($promotion->getId ())
                        ->setQueueId ($queue->getId ())
                        ->setContactId ($entityId)
                        ->setIsDelivered (0)
                        ->setIsError (0)
                        ->setCreatedAt (date ('c'))
                        ->save ()
                    ;
                }), array ('queue' => $queue, 'promotion' => $promotion));

				Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('bot')->__('Promotion was successfully sent to the queue.'));

				$this->_redirect ('*/adminhtml_queue/index');

			    return $this;
			}
			catch (Exception $e)
            {
				Mage::getSingleton ('adminhtml/session')->addError ($e->getMessage ());

				$this->_redirect ('*/adminhtml_promotion/edit', array ('id' => $this->getRequest ()->getParam ('id')));

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
        $fileName = 'promotions.csv';
        $grid     = $this->getLayout()->createBlock('bot/adminhtml_promotion_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'promotions.xml';
        $grid       = $this->getLayout()->createBlock('bot/adminhtml_promotion_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

