<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Adminhtml_ChatController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed ()
	{
	    return Mage::getSingleton ('admin/session')->isAllowed ('toluca/bot/chat');
	}

	protected function _initAction ()
	{
		$this->loadLayout ()->_setActiveMenu ('toluca/bot/chat')
            ->_addBreadcrumb(
                Mage::helper ('bot')->__('Chats Manager'),
                Mage::helper ('bot')->__('Chats Manager')
            )
        ;

		return $this;
	}

    protected function _initChat()
    {
        $id = $this->getRequest ()->getParam ('id');

        $chat = Mage::getModel ('bot/chat')->load ($id);

        if (!$chat || !$chat->getId ())
        {
            $this->_getSession ()->addError ($this->__('This chat no longer exists.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        $collection = Mage::getModel ('bot/message')->getCollection ()
            ->addFieldToFilter ('chat_id', array ('eq' => $chat->getId ()))
        ;

        if (!$collection->getSize ())
        {
            $this->_getSession ()->addError ($this->__('This chat has no messages.'));

            $this->_redirect('*/*/index');

            $this->setFlag('', self::FLAG_NO_DISPATCH, true);

            return false;
        }

        Mage::register('bot_chat',     $chat);
        Mage::register('current_chat', $chat);

        return $chat;
    }

	public function indexAction ()
	{
	    $this->_title ($this->__('Bot'));
	    $this->_title ($this->__('Chats Manager'));

		$this->_initAction ();

		$this->renderLayout ();
	}

    public function messageAction ()
    {
        $chat = $this->_initChat ();

        if ($chat && $chat->getId ())
        {
	        $this->_title ($this->__('Bot'));
	        $this->_title ($this->__('Messages History'));

		    $this->_initAction ();

		    $this->renderLayout ();
        }
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'chats.csv';
        $grid     = $this->getLayout()->createBlock('bot/adminhtml_chat_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'chats.xml';
        $grid       = $this->getLayout()->createBlock('bot/adminhtml_chat_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}

