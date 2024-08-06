<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Newsletter_Observer extends Mage_Newsletter_Model_Observer
{
    const COUNT_OF_QUEUE = 3;
    const COUNT_OF_SUBSCRIPTIONS = 20;

    /**
     * @param Varien_Event_Observer $schedule
     */
    public function scheduledSend($schedule)
    {
        $countOfQueue        = Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_NEWSLETTER_SCHEDULED_SEND_COUNT_OF_QUEUE);
        $countOfSubscritions = Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_NEWSLETTER_SCHEDULED_SEND_COUNT_OF_SUBSCRIPTIONS);

        /** @var Mage_Newsletter_Model_Resource_Queue_Collection $collection */
        $collection = Mage::getModel('newsletter/queue')->getCollection()
            ->setPageSize($countOfQueue ?? self::COUNT_OF_QUEUE)
            ->setCurPage(1)
            ->addOnlyForSendingFilter()
            ->load();

        $collection->walk('sendPerSubscriber', [$countOfSubscritions ?: self::COUNT_OF_SUBSCRIPTIONS]);
    }
}

