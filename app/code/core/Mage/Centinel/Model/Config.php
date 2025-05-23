<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Centinel
 */

/**
 * @package    Mage_Centinel
 */
class Mage_Centinel_Model_Config
{
    /**
     * Store id or store model
     *
     * @var int|Mage_Core_Model_Store|false
     */
    protected $_store = false;

    /**
     * Path of centinel config
     *
     * @var string
     */
    protected $_serviceConfigPath = 'payment_services/centinel';

    /**
     * Path of cards config
     *
     * @var string
     */
    protected $_cardTypesConfigPath = 'global/payment/cc/types';

    /**
     * Set store to congif model
     *
     * @param int|Mage_Core_Model_Store $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Return store
     *
     * @return int|Mage_Core_Model_Store
     */
    public function getStore()
    {
        return $this->_store;
    }

    /**
     * Return validation state class for card with type $cardType
     *
     * @param string $cardType
     * @return string|array|false
     */
    public function getStateModelClass($cardType)
    {
        $node = Mage::getConfig()->getNode($this->_cardTypesConfigPath . '/' . $cardType . '/validator/centinel/state');
        if (!$node) {
            return false;
        }
        return $node->asArray();
    }

    /**
     * Return centinel processorId
     *
     * @return string
     */
    public function getProcessorId()
    {
        return $this->_getServiceConfigValue('processor_id');
    }

    /**
     * Return centinel merchantId
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->_getServiceConfigValue('merchant_id');
    }

    /**
     * Return centinel transactionPwd
     *
     * @return string
     */
    public function getTransactionPwd()
    {
        return Mage::helper('core')->decrypt($this->_getServiceConfigValue('password'));
    }

    /**
     * Return flag - is centinel mode test
     *
     * @return bool
     */
    public function getIsTestMode()
    {
        return (bool) (int) $this->_getServiceConfigValue('test_mode');
    }

    /**
     * Return value of node of centinel config section
     *
     * @param string $key
     * @return string
     */
    private function _getServiceConfigValue($key)
    {
        return Mage::getStoreConfig($this->_serviceConfigPath . '/' . $key, $this->getStore());
    }

    /**
     * Define if debugging is enabled
     *
     * @return string
     */
    public function getDebugFlag()
    {
        return $this->_getServiceConfigValue('debug');
    }
}
