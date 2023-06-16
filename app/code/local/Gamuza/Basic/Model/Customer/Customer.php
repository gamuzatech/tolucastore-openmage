<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer model
 */
class Gamuza_Basic_Model_Customer_Customer
    extends Mage_Customer_Model_Customer
{
    public const ENTITY = 'customer';

    /**#@+
     * Codes of exceptions related to customer model
     */
    public const EXCEPTION_CELLPHONE_EXISTS = 6;

    public const XML_PATH_GENERATE_HUMAN_FRIENDLY_EMAIL = 'customer/create_account/generate_human_friendly_email';

    /**
     * Send corresponding email template
     *
     * @param string $template configuration path of email template
     * @param string $sender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @param string|null $customerEmail
     * @return $this
     */
    protected function _sendEmailTemplate($template, $sender, $templateParams = [], $storeId = null, $customerEmail = null)
    {
        $customerEmail = ($customerEmail) ? $customerEmail : $this->getEmail();

        /** @var Mage_Core_Model_Email_Template_Mailer $mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($customerEmail, $this->getName());
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig($sender, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId(Mage::getStoreConfig($template, $storeId));
        $mailer->setTemplateParams($templateParams);

        /** @var Mage_Core_Model_Email_Queue $emailQueue */
        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($this->getId())
            ->setEntityType(self::ENTITY)
            ->setEventType($sender)
            ->setIsForceCheck(true);

        $mailer->setQueue($emailQueue)->send();

        return $this;
    }
}

