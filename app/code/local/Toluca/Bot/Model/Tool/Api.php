<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Tool API
 */
class Toluca_Bot_Model_Tool_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ($filters = null, $limit = 2)
    {
        $result = array ();

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);

        $collection = Mage::getModel ('bot/chat')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            /*
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            */
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            /*
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
            */
        ;

        /** @var $apiHelper Mage_Api_Helper_Data */
        $apiHelper = Mage::helper ('api');

        $filters = $apiHelper->parseFilters ($filters, $this->_filtersMap);

        try
        {
            foreach ($filters as $field => $value)
            {
                $collection->addFieldToFilter ($field, $value);
            }
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('filters_invalid', $e->getMessage ());
        }

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        if ($collection->count () > 0)
        {
            $chat = $collection->getFirstItem ();

            $collection = Mage::getModel ('bot/tool')->getCollection ()
                ->addFieldToFilter ('chat_id', array ('eq' => $chat->getId ()))
            ;

            $collection->getSelect ()
                ->order ('created_at DESC')
                ->limit ($limit)
            ;

            foreach ($collection as $tool)
            {
                $result [] = array(
                    'entity_id'  => intval ($tool->getId ()),
                    'chat_id'    => intval ($tool->getChatId ()),
                    'message_id' => intval ($tool->getChatId ()),
                    'bot_type'   => $tool->getBotType (),
                    'type_id'    => $tool->getTypeId (),
                    'remote_ip'  => $tool->getRemoteIp (),
                    'user_agent' => $tool->getUserAgent (),
                    'name'       => $tool->getName (),
                    'path'       => $tool->getPath (),
                    'body'       => $tool->getBody (),
                    'result'     => $tool->getResult (),
                    'created_at' => $tool->getCreatedAt (),
                    'chat'       => array(
                        'is_muted' => $chat->getIsMuted (),
                    )
                );
            }
        }

        return $result;
    }

    public function add ($type, $name, $path, $body = null, $result = null, $message = null)
    {
        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);

        $collection = Mage::getModel ('bot/chat')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            /*
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            */
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            /*
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
            */
        ;

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        $chat = $collection->getFirstItem ();

        if (!empty ($senderName))
        {
             $senderName = explode (' ', $senderName, 2);
        }

        if (is_array ($senderName) && count ($senderName) == 1)
        {
            $senderName [1] = '------';
        }

        if (!$senderName || !is_array ($senderName) || count ($senderName) != 2)
        {
            $senderName = array(
                0 => Mage::helper ('bot')->__('Firstname'),
                1 => Mage::helper ('bot')->__('Lastname'),
            );
        }

        $remoteIp = Mage::helper ('bot')->getRemoteIp ();
        $userAgent = Mage::helper ('bot')->getUserAgent ();

        if (!$collection->count ())
        {
            $chat = Mage::getModel ('bot/chat')
                ->setIsActive (true)
                ->setTypeId ($botType)
                ->setStoreId ($storeId)
                ->setQuoteId ($quote->getId ())
                ->setNumber ($from)
                ->setPhone ($to)
                ->setFirstname ($senderName [0])
                ->setLastname ($senderName [1])
                ->setRemoteIp ($remoteIp)
                ->setUserAgent ($userAgent)
                ->setEmail (self::DEFAULT_CUSTOMER_EMAIL)
                ->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                ->setCreatedAt (date ('c'))
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;
        }

        $tool = $this->_saveTool ($name, $path, $chat, $type, $body, $result, $message);

        return intval ($tool->getId ());
    }
}

