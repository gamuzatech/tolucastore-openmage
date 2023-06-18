<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Core_Resource_Email_Queue
    extends Mage_Core_Model_Resource_Email_Queue
{
    /**
     * Load recipients, unserialize message parameters
     *
     * @param Mage_Core_Model_Email_Queue $object
     * @inheritDoc
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $object->setRecipients($this->getRecipients($object->getId()));
        $object->setMessageParameters(json_decode($object->getMessageParameters(), true));

        return $this;
    }

    /**
     * Prepare object data for saving
     *
     * @param Mage_Core_Model_Email_Queue $object
     * @inheritDoc
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew())
        {
            $object->setCreatedAt($this->formatDate(true));
        }

        $object->setMessageBodyHash(md5($object->getMessageBody()));
        $object->setMessageParameters(json_encode($object->getMessageParameters()));

        return $this;
    }
}

