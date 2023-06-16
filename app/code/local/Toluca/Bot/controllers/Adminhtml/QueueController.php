<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Adminhtml_QueueController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/bot/queue');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('bot/queue')
            ->_addBreadcrumb(
                Mage::helper ('bot')->__('Queue Manager'),
                Mage::helper ('bot')->__('Queue Manager')
            )
        ;

		return $this;
	}

    protected function _initQueue()
    {
        $id = $this->getRequest ()->getParam ('id');

        $queue = Mage::getModel ('bot/queue')->load ($id);

        if (!$queue || !$queue->getId ())
        {
            $this->_getSession ()->addError ($this->__('This queue no longer exists.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        $collection = Mage::getModel ('bot/log')->getCollection ()
            ->addFieldToFilter ('queue_id', array ('eq' => $queue->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_getSession ()->addError ($this->__('This queue has no history.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        Mage::register('bot_queue',     $queue);
        Mage::register('current_queue', $queue);

        return $queue;
    }

	public function indexAction ()
	{
	    $this->_title ($this->__('Bot'));
	    $this->_title ($this->__('Queue Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    public function historyAction ()
    {
        $queue = $this->_initQueue ();

        if ($queue && $queue->getId ())
        {
	        $this->_title ($this->__('Bot'));
	        $this->_title ($this->__('History Manager'));

		    $this->_initAction ();

		    $this->renderLayout ();
        }
    }

    public function massStatusAction ()
    {
        $queueIds = $this->getRequest()->getParam('queue');
        $status   = $this->getRequest()->getParam('status');

        $statuses = Mage::getModel ('bot/adminhtml_system_config_source_queue_status')->toArray ();

        try
        {
            foreach ($queueIds as $id)
            {
                $queue = Mage::getSingleton ('bot/queue')->load ($id);

                $queueStatus = $queue->getStatus ();

                if (!in_array ($queueStatus, array(
                    Toluca_Bot_Helper_Data::QUEUE_STATUS_PENDING,
                    Toluca_Bot_Helper_Data::QUEUE_STATUS_SENDING,
                )))
                {
                    throw new Mage_Core_Exception (Mage::helper ('bot')->__('Invalid queue status: %s', $statuses [$queueStatus]));
                }

                if ((!strcmp ($queueStatus, Toluca_Bot_Helper_Data::QUEUE_STATUS_PENDING)
                    && strcmp ($status, Toluca_Bot_Helper_Data::QUEUE_STATUS_CANCELED))
                    || (!strcmp ($queueStatus, Toluca_Bot_Helper_Data::QUEUE_STATUS_SENDING)
                    && strcmp ($status, Toluca_Bot_Helper_Data::QUEUE_STATUS_STOPPED))
                )
                {
                    throw new Mage_Core_Exception (
                        Mage::helper ('bot')->__('Invalid queue status: %s for %s',
                            $statuses [$status],
                            $statuses [$queueStatus],
                        )
                    );
                }

                $queue->setStatus ($status)->save ();
            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($queueIds))
            );
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_getSession ()->addError ($e->getMessage ());
        }
        catch (Exception $e)
        {
            $this->_getSession ()
                ->addException ($e, $this->__('An error occurred while updating the queue(s) status.')
            );
        }

        $this->_redirect ('*/*/index');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'queues.csv';
        $grid     = $this->getLayout()->createBlock('bot/adminhtml_queue_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'queues.xml';
        $grid       = $this->getLayout()->createBlock('bot/adminhtml_queue_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

