<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Authorizenet
 */

/**
 * Authorize.net DirectPost payment method model.
 *
 * @package    Mage_Authorizenet
 */
class Mage_Authorizenet_Model_Directpost extends Mage_Paygate_Model_Authorizenet
{
    protected $_code  = 'authorizenet_directpost';
    protected $_formBlockType = 'directpost/form';
    protected $_infoBlockType = 'payment/info';

    /**
     * Availability options
     */
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid                 = true;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;
    protected $_canSaveCc               = false;
    protected $_isInitializeNeeded      = true;
    protected $_canFetchTransactionInfo = false;

    /**
     * Do not validate payment form using server methods
     *
     * @return  bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Send authorize request to gateway
     *
     * @param  float $amount
     * @return void
     * @throws Mage_Core_Exception
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $payment->setAdditionalInformation('payment_type', $this->getConfigData('payment_action'));
    }

    /**
     * Send capture request to gateway
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param float $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function capture(Varien_Object $payment, $amount)
    {
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid amount for capture.'));
        }

        $payment->setAmount($amount);

        if ($payment->getParentTransactionId()) {
            $payment->setAnetTransType(self::REQUEST_TYPE_PRIOR_AUTH_CAPTURE);
            $payment->setXTransId($this->_getRealParentTransactionId($payment));
        } else {
            $payment->setAnetTransType(self::REQUEST_TYPE_AUTH_CAPTURE);
        }

        $request = $this->_buildRequest($payment);
        $result = $this->_postRequest($request);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    if (!$payment->getParentTransactionId() ||
                        $result->getTransactionId() != $payment->getParentTransactionId()
                    ) {
                        $payment->setTransactionId($result->getTransactionId());
                    }
                    $payment
                        ->setIsTransactionClosed(0)
                        ->setTransactionAdditionalInfo($this->_realTransactionIdKey, $result->getTransactionId());
                    return $this;
                }
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            default:
                Mage::throwException(Mage::helper('paygate')->__('Payment capturing error.'));
        }
    }

    /**
     * Check refund availability
     *
     * @return bool
     */
    public function canRefund()
    {
        return $this->_canRefund;
    }

    /**
     * Check void availability
     *
     * @return  bool
     */
    public function canVoid(Varien_Object $payment)
    {
        return $this->_canVoid;
    }

    /**
     * Void the payment through gateway
     *
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function void(Varien_Object $payment)
    {
        if (!$payment->getParentTransactionId()) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid transaction ID.'));
        }

        $payment->setAnetTransType(self::REQUEST_TYPE_VOID);
        $payment->setXTransId($this->_getRealParentTransactionId($payment));

        $request = $this->_buildRequest($payment);
        $result = $this->_postRequest($request);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    if ($result->getTransactionId() != $payment->getParentTransactionId()) {
                        $payment->setTransactionId($result->getTransactionId());
                    }
                    $payment
                        ->setIsTransactionClosed(1)
                        ->setShouldCloseParentTransaction(1)
                        ->setTransactionAdditionalInfo($this->_realTransactionIdKey, $result->getTransactionId());
                    return $this;
                }
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            default:
                Mage::throwException(Mage::helper('paygate')->__('Payment voiding error.'));
        }
    }

    /**
     * Set capture transaction ID to invoice for informational purposes
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function processInvoice($invoice, $payment)
    {
        return Mage_Payment_Model_Method_Abstract::processInvoice($invoice, $payment);
    }

    /**
     * Set transaction ID into creditmemo for informational purposes
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function processCreditmemo($creditmemo, $payment)
    {
        return Mage_Payment_Model_Method_Abstract::processCreditmemo($creditmemo, $payment);
    }

    /**
     * Refund the amount
     * Need to decode Last 4 digits for request.
     *
     * @param float $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $last4 = $payment->getCcLast4();
        $payment->setCcLast4($payment->decrypt($last4));
        try {
            $this->_refund($payment, $amount);
        } catch (Exception $e) {
            $payment->setCcLast4($last4);
            throw $e;
        }
        $payment->setCcLast4($last4);
        return $this;
    }

    /**
     * refund the amount with transaction id
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param string $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    protected function _refund(Varien_Object $payment, $amount)
    {
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid amount for refund.'));
        }

        if (!$payment->getParentTransactionId()) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid transaction ID.'));
        }

        $payment->setAnetTransType(self::REQUEST_TYPE_CREDIT);
        $payment->setAmount($amount);
        $payment->setXTransId($this->_getRealParentTransactionId($payment));

        $request = $this->_buildRequest($payment);
        $result = $this->_postRequest($request);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    if ($result->getTransactionId() != $payment->getParentTransactionId()) {
                        $payment->setTransactionId($result->getTransactionId());
                    }
                    $shouldCloseCaptureTransaction = $payment->getOrder()->canCreditmemo() ? 0 : 1;
                    $payment
                         ->setIsTransactionClosed(1)
                         ->setShouldCloseParentTransaction($shouldCloseCaptureTransaction)
                         ->setTransactionAdditionalInfo($this->_realTransactionIdKey, $result->getTransactionId());
                    return $this;
                }
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
                // no break
            default:
                Mage::throwException(Mage::helper('paygate')->__('Payment refunding error.'));
        }
    }

    /**
     * Get CGI url
     *
     * @return string
     */
    public function getCgiUrl()
    {
        $uri = $this->getConfigData('cgi_url');
        return $uri ? $uri : self::CGI_URL;
    }

    /**
     * Return URL on which Authorize.net server will return payment result data in hidden request.
     *
     * @param int $storeId
     * @return string
     */
    public function getRelayUrl($storeId = null)
    {
        if ($storeId == null && $this->getStore()) {
            $storeId = $this->getStore();
        }
        return Mage::app()->getStore($storeId)
            ->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) .
            'authorizenet/directpost_payment/response';
    }

    /**
     * Return request model for form data building
     *
     * @return Mage_Authorizenet_Model_Directpost_Request
     */
    protected function _getRequestModel()
    {
        return Mage::getModel('authorizenet/directpost_request');
    }

    /**
     * Return response.
     *
     * @return Mage_Authorizenet_Model_Directpost_Response
     */
    public function getResponse()
    {
        return Mage::getSingleton('authorizenet/directpost_response');
    }

    /**
     * Instantiate state and set it to state object
     *
     * @param string $paymentAction
     * @param Varien_Object $stateObject
     */
    public function initialize($paymentAction, $stateObject)
    {
        switch ($paymentAction) {
            case self::ACTION_AUTHORIZE:
            case self::ACTION_AUTHORIZE_CAPTURE:
                /** @var Mage_Sales_Model_Order_Payment|Mage_Sales_Model_Quote_Payment $payment */
                $payment = $this->getInfoInstance();
                $order = $payment->getOrder();
                $order->setCanSendNewEmailFlag(false);
                $payment->authorize(true, $order->getBaseTotalDue()); // base amount will be set inside
                $payment->setAmountAuthorized($order->getTotalDue());

                $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, 'pending_payment', '', false);

                $stateObject->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
                $stateObject->setStatus('pending_payment');
                $stateObject->setIsNotified(false);
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Generate request object and fill its fields from Quote or Order object
     *
     * @param Mage_Sales_Model_Order $order Quote or order object.
     * @return Mage_Authorizenet_Model_Directpost_Request
     */
    public function generateRequestFromOrder(Mage_Sales_Model_Order $order)
    {
        $request = $this->_getRequestModel();
        $request->setConstantData($this)
            ->setDataFromOrder($order, $this)
            ->signRequestData();

        $this->_debug(['request' => $request->getData()]);

        return $request;
    }

    /**
     * Fill response with data.
     *
     * @return $this
     */
    public function setResponseData(array $postData)
    {
        $this->getResponse()->setData($postData);
        return $this;
    }

    /**
     * Validate response data. Needed in controllers.
     *
     * @return bool true in case of validation success.
     * @throws Mage_Core_Exception in case of validation error
     */
    public function validateResponse()
    {
        $response = $this->getResponse();
        $xSHA2Hash = $response->getData('x_SHA2_Hash');
        $hashConfigKey = !empty($xSHA2Hash) ? 'signature_key' : 'trans_md5';

        //hash check
        if (!$this->getConfigData($hashConfigKey)
            || !$response->isValidHash($this->getConfigData($hashConfigKey), $this->getConfigData('login'))
        ) {
            Mage::throwException(
                Mage::helper('authorizenet')->__('Response hash validation failed. Transaction declined.'),
            );
        }
        return true;
    }

    /**
     * Operate with order using data from $_POST which came from authorize.net by Relay URL.
     *
     * @param array $responseData data from Authorize.net from $_POST
     * @throws Mage_Core_Exception in case of validation error or order creation error
     */
    public function process(array $responseData)
    {
        $debugData = [
            'response' => $responseData,
        ];
        $this->_debug($debugData);

        $this->setResponseData($responseData);

        //check MD5 error or others response errors
        //throws exception on false.
        $this->validateResponse();

        $response = $this->getResponse();
        //operate with order
        $orderIncrementId = $response->getXInvoiceNum();
        $responseText = $this->_wrapGatewayError($response->getXResponseReasonText());
        $isError = false;
        if ($orderIncrementId) {
            /** @var Mage_Sales_Model_Order $order */
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
            //check payment method
            $payment = $order->getPayment();
            if (!$payment || $payment->getMethod() != $this->getCode()) {
                Mage::throwException(
                    Mage::helper('authorizenet')->__('Payment error. Order was not found.'),
                );
            }
            if ($order->getId() &&  $order->getState() == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) {
                //operate with order
                $this->_authOrder($order);
            } else {
                $isError = true;
            }
        } else {
            $isError = true;
        }

        if ($isError) {
            Mage::throwException(
                ($responseText && !$response->isApproved()) ?
                $responseText :
                Mage::helper('authorizenet')->__('Payment error. Order was not found.'),
            );
        }
    }

    /**
     * Fill payment with credit card data from response from Authorize.net.
     */
    protected function _fillPaymentByResponse(Varien_Object $payment)
    {
        $response = $this->getResponse();
        $payment->setTransactionId($response->getXTransId())
            ->setParentTransactionId(null)
            ->setIsTransactionClosed(0)
            ->setTransactionAdditionalInfo($this->_realTransactionIdKey, $response->getXTransId());

        if ($response->getXMethod() == self::REQUEST_METHOD_CC) {
            $payment->setCcAvsStatus($response->getXAvsCode())
                ->setCcLast4($payment->encrypt(substr($response->getXAccountNumber(), -4)));
        }
    }

    /**
     * Check response code came from authorize.net.
     *
     * @return true in case of Approved response
     * @throws Mage_Core_Exception in case of Declined or Error response from Authorize.net
     */
    public function checkResponseCode()
    {
        switch ($this->getResponse()->getXResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                return true;
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                Mage::throwException($this->_wrapGatewayError($this->getResponse()->getXResponseReasonText()));
                // no break
            default:
                Mage::throwException(Mage::helper('authorizenet')->__('Payment authorization error.'));
        }
    }

    /**
     * Check transaction id came from Authorize.net
     *
     * @return true in case of right transaction id
     * @throws Mage_Core_Exception in case of bad transaction id.
     */
    public function checkTransId()
    {
        if (!$this->getResponse()->getXTransId() && ($this->getResponse()->getXTransId() !== '0')) {
            Mage::throwException(
                Mage::helper('authorizenet')->__('Payment authorization error. Transacion id is empty.'),
            );
        }
        return true;
    }

    /**
     * Compare amount with amount from the response from Authorize.net.
     *
     * @param float $amount
     * @return bool
     */
    protected function _matchAmount($amount)
    {
        return sprintf('%.2F', $amount) == sprintf('%.2F', $this->getResponse()->getXAmount());
    }

    /**
     * Operate with order using information from Authorize.net.
     * Authorize order or authorize and capture it.
     *
     *
     * @throws Exception
     */
    protected function _authOrder(Mage_Sales_Model_Order $order)
    {
        try {
            $this->checkResponseCode();
            $this->checkTransId();
        } catch (Exception $e) {
            //decline the order (in case of wrong response code) but don't return money to customer.
            $message = $e->getMessage();
            $this->_declineOrder($order, $message, false);
            throw $e;
        }

        $response = $this->getResponse();

        //create transaction. need for void if amount will not match.
        $payment = $order->getPayment();
        $this->_fillPaymentByResponse($payment);

        $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH);

        // Set transaction approval message
        $message = Mage::helper('authorizenet')->__('Amount of %s approved by payment gateway. Transaction ID: "%s".', $order->getBaseCurrency()->formatTxt($payment->getBaseAmountAuthorized()), $response->getXTransId());

        $orderState = Mage_Sales_Model_Order::STATE_PROCESSING;
        $orderStatus = $this->getConfigData('order_status');
        if (!$orderStatus || $order->getIsVirtual()) {
            $orderStatus = $order->getConfig()->getStateDefaultStatus($orderState);
        }

        $order->setState($orderState, $orderStatus ? $orderStatus : true, $message, false)
            ->save();

        //match amounts. should be equals for authorization.
        //decline the order if amount does not match.
        if (!$this->_matchAmount($payment->getBaseAmountAuthorized())) {
            $message = Mage::helper('authorizenet')->__('Payment error. Paid amount doesn\'t match the order amount.');
            $this->_declineOrder($order, $message, true);
            Mage::throwException($message);
        }

        //capture order using AIM if needed
        $this->_captureOrder($order);

        try {
            if (!$response->hasOrderSendConfirmation() || $response->getOrderSendConfirmation()) {
                $order->queueNewOrderEmail();
            }

            Mage::getModel('sales/quote')
                ->load($order->getQuoteId())
                ->setIsActive(false)
                ->save();
        } catch (Exception $e) {
            Mage::logException($e); // do not cancel order if we couldn't send email
        }
    }

    /**
     * Register order cancellation. Return money to customer if needed.
     *
     * @param string $message
     * @param bool $voidPayment
     */
    protected function _declineOrder(Mage_Sales_Model_Order $order, $message = '', $voidPayment = true)
    {
        try {
            $response = $this->getResponse();
            if ($voidPayment &&
                $response->getXTransId() &&
                strtoupper($response->getXType()) == self::REQUEST_TYPE_AUTH_ONLY
            ) {
                $order->getPayment()
                    ->setTransactionId(null)
                    ->setParentTransactionId($response->getXTransId())
                    ->void();
            }
            $order->registerCancellation($message)
                ->save();
        } catch (Exception $e) {
            //quiet decline
            Mage::logException($e);
        }
    }

    /**
     * Capture order's payment using AIM.
     */
    protected function _captureOrder(Mage_Sales_Model_Order $order)
    {
        $payment = $order->getPayment();
        if ($payment->getAdditionalInformation('payment_type') == self::ACTION_AUTHORIZE_CAPTURE) {
            try {
                $payment->setTransactionId(null)
                    ->setParentTransactionId($this->getResponse()->getXTransId())
                    ->capture(null);

                // set status from config for AUTH_AND_CAPTURE orders.
                if ($order->getState() == Mage_Sales_Model_Order::STATE_PROCESSING) {
                    $orderStatus = $this->getConfigData('order_status');
                    if (!$orderStatus || $order->getIsVirtual()) {
                        $orderStatus = $order->getConfig()
                                ->getStateDefaultStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
                    }
                    if ($orderStatus) {
                        $order->setStatus($orderStatus);
                    }
                }

                $order->save();
            } catch (Exception $e) {
                Mage::logException($e);
                //if we couldn't capture order, just leave it as NEW order.
            }
        }
    }

    /**
     * Return additional information`s transaction_id value of parent transaction model
     *
     * @param Mage_Sales_Model_Order_Payment|Varien_Object $payment
     * @return string
     */
    protected function _getRealParentTransactionId($payment)
    {
        $transaction = $payment->getTransaction($payment->getParentTransactionId());
        return $transaction->getAdditionalInformation($this->_realTransactionIdKey);
    }
}
