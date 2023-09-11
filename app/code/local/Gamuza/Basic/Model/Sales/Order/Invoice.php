<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Sales order invoice model
 */
class Gamuza_Basic_Model_Sales_Order_Invoice
    extends Mage_Sales_Model_Order_Invoice
{
    /**
     * Identifier for invoice
     */
    public const ENTITY = 'invoice';

    /**
     * Event type names for invoice emails
     */
    public const EMAIL_EVENT_NAME_NEW_INVOICE    = 'new_invoice';
    public const EMAIL_EVENT_NAME_UPDATE_INVOICE = 'update_invoice';

    /**
     * Send email with invoice data
     *
     * @param bool $notifyCustomer
     * @param string $comment
     * @return $this
     */
    public function sendEmail($notifyCustomer = true, $comment = '')
    {
        if (Mage::getStoreConfig('smtppro/queue/usage') == 'never')
        {
            return parent::sendEmail($notifyCustomer, $comment);
        }

        $order = $this->getOrder();
        $storeId = $order->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewInvoiceEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);
        // Check if at least one recepient is found
        if (!$notifyCustomer && !$copyTo) {
            return $this;
        }

        // Start store emulation process
        if ($storeId != Mage::app()->getStore()->getId()) {
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
        }

        try {
            // Retrieve specified view block from appropriate design package (depends on emulated store)
            $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment())
                ->setIsSecureMode(true);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
        } catch (Exception $e) {
            // Stop store emulation process
            if (isset($appEmulation, $initialEnvironmentInfo)) {
                $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            }
            throw $e;
        }

        // Stop store emulation process
        if (isset($appEmulation, $initialEnvironmentInfo)) {
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
        }

        // Retrieve corresponding email template id and customer name
        if ($order->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
            $customerName = $order->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        if ($notifyCustomer) {
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($order->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
                // Add bcc to customer email
                foreach ($copyTo as $email) {
                    $emailInfo->addBcc($email);
                }
            }
            $mailer->addEmailInfo($emailInfo);
        }

        // Email copies are sent as separated emails if their copy method is 'copy' or a customer should not be notified
        if ($copyTo && ($copyMethod == 'copy' || !$notifyCustomer)) {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams([
                'order'        => $order,
                'invoice'      => $this,
                'comment'      => $comment,
                'billing'      => $order->getBillingAddress(),
                'payment_html' => $paymentBlockHtml
        ]);

        /** @var Mage_Core_Model_Email_Queue $emailQueue */
        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($this->getId())
            ->setEntityType(self::ENTITY)
            ->setEventType(self::EMAIL_EVENT_NAME_NEW_INVOICE)
            ->setIsForceCheck(true);

        $mailer->setQueue($emailQueue)->send();

        if ($notifyCustomer) {
            $this->setEmailSent(true);
            $this->_getResource()->saveAttribute($this, 'email_sent');
        }

        return $this;
    }

    /**
     * Send email with invoice update information
     *
     * @param bool $notifyCustomer
     * @param string $comment
     * @return $this
     */
    public function sendUpdateEmail($notifyCustomer = true, $comment = '')
    {
        if (Mage::getStoreConfig('smtppro/queue/usage') == 'never')
        {
            return parent::sendUpdateEmail($notifyCustomer, $comment);
        }

        $order = $this->getOrder();
        $storeId = $order->getStore()->getId();

        if (!Mage::helper('sales')->canSendInvoiceCommentEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_UPDATE_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_COPY_METHOD, $storeId);
        // Check if at least one recepient is found
        if (!$notifyCustomer && !$copyTo) {
            return $this;
        }

        // Retrieve corresponding email template id and customer name
        if ($order->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId);
            $customerName = $order->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        if ($notifyCustomer) {
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($order->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
                // Add bcc to customer email
                foreach ($copyTo as $email) {
                    $emailInfo->addBcc($email);
                }
            }
            $mailer->addEmailInfo($emailInfo);
        }

        // Email copies are sent as separated emails if their copy method is 'copy' or a customer should not be notified
        if ($copyTo && ($copyMethod == 'copy' || !$notifyCustomer)) {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams([
                'order'        => $order,
                'invoice'      => $this,
                'comment'      => $comment,
                'billing'      => $order->getBillingAddress()
        ]);

        /** @var Mage_Core_Model_Email_Queue $emailQueue */
        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($this->getId())
            ->setEntityType(self::ENTITY)
            ->setEventType(self::EMAIL_EVENT_NAME_UPDATE_INVOICE)
            ->setIsForceCheck(true);

        $mailer->setQueue($emailQueue)->send();

        return $this;
    }
}

