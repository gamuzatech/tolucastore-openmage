<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_AdminNotification_Inbox_Api
    extends Mage_Api_Model_Resource_Abstract
{
    public function items ($filters = null)
    {
        $collection = Mage::getModel ('adminnotification/inbox')->getCollection ()
            ->setOrder ('date_added', 'DESC')
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

        $result = array ();

        foreach ($collection as $inbox)
        {
            $url = $inbox->getUrl ();
            $url = filter_var($url, FILTER_VALIDATE_URL) ? $url : null;

            $result [] = array(
                'notification_id' => intval ($inbox->getId ()),
                'severity'        => intval ($inbox->getSeverity ()),
                'date_added'      => strval ($inbox->getDateAdded ()),
                'title'           => strval ($inbox->getTitle ()),
                'description'     => strval ($inbox->getDescription ()),
                'url'             => $url,
                'is_read'         => boolval ($inbox->getIsRead ()),
                'is_remove'       => boolval ($inbox->getIsRemove ()),
            );
        }

        return $result;
    }

    public function add ($inboxesData = null)
    {
        if (empty ($inboxesData))
        {
            $this->_fault ('inbox_data_not_specified');
        }

        $inboxesData = $this->_prepareInboxesData($inboxesData);

        if (empty ($inboxesData))
        {
            $this->_fault('invalid_inbox_data');
        }

        $errors = array ();

        foreach ($inboxesData as $inboxData)
        {
            try
            {
                Mage::getModel ('adminnotification/inbox')
                    ->addData ($inboxData)
                    ->setData ('notification_id', null)
                    ->setData ('date_added', date ('c'))
                    ->save ()
                ;
            }
            catch(Exception $e)
            {
                $errors [] = $e->getMessage ();
            }
        }

        if (!empty ($errors))
        {
            $this->_fault('add_inbox_fault', implode (PHP_EOL, $errors));
        }

        return true;
    }

    /**
     * Base preparation of inbox data
     *
     * @param  mixed $data
     * @return null|array
     */
    protected function _prepareInboxesData ($data)
    {
        return is_array ($data) ? $data : null;
    }
}

