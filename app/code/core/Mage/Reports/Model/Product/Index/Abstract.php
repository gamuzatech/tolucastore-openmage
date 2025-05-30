<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Reports
 */

/**
 * Reports Product Index Abstract Model
 *
 * @package    Mage_Reports
 *
 * @method Mage_Reports_Model_Resource_Product_Index_Abstract _getResource()
 * @method Mage_Reports_Model_Resource_Product_Index_Collection_Abstract getCollection()
 * @method bool hasAddedAt()
 * @method $this setAddedAt(string $value)
 * @method bool hasCustomerId()
 * @method $this setCustomerId(int $value)
 * @method bool hasStoreId()
 * @method $this setStoreId(int $value)
 * @method bool hasVisitorId()
 * @method $this setVisitorId(int $value)
 */
abstract class Mage_Reports_Model_Product_Index_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Cache key name for Count of product index
     *
     * @var string
     */
    protected $_countCacheKey;

    /**
     * Save object data
     *
     * @see Mage_Core_Model_Abstract::save()
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function save()
    {
        if (!$this->hasVisitorId()) {
            $this->setVisitorId($this->getVisitorId());
        }
        if (!$this->hasCustomerId()) {
            $this->setCustomerId($this->getCustomerId());
        }
        if (!$this->hasStoreId()) {
            $this->setStoreId($this->getStoreId());
        }
        if (!$this->hasAddedAt()) {
            $this->setAddedAt(Varien_Date::now());
        }

        // Thanks to new performance tweaks it is possible to switch off visitor logging
        // This check is needed to make sure report record has either visitor id or customer id
        if ($this->hasVisitorId() || $this->hasCustomerId()) {
            try {
                parent::save();
            } catch (Exception $exception) {
                if ($this->hasCustomerId()) {
                    $this->updateCustomerFromVisitor();
                    parent::save();
                } else {
                    Mage::logException($exception);
                }
            }
        }

        return $this;
    }

    /**
     * Retrieve visitor id
     *
     * if don't exists return current visitor id
     *
     * @return int
     */
    public function getVisitorId()
    {
        if ($this->hasData('visitor_id')) {
            return $this->getData('visitor_id');
        }
        return Mage::getSingleton('log/visitor')->getId();
    }

    /**
     * Retrieve customer id
     *
     * if customer don't logged in return null
     *
     * @return int
     */
    public function getCustomerId()
    {
        if ($this->hasData('customer_id')) {
            return $this->getData('customer_id');
        }
        return Mage::getSingleton('customer/session')->getCustomerId();
    }

    /**
     * Retrieve store id
     *
     * default return current store id
     *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData('store_id')) {
            return (int) $this->getData('store_id');
        }
        return Mage::app()->getStore()->getId();
    }

    /**
     * On customer login merge visitor/customer index
     *
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function updateCustomerFromVisitor()
    {
        $this->_getResource()->updateCustomerFromVisitor($this);
        return $this;
    }

    /**
     * Purge visitor data by customer (logout)
     *
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function purgeVisitorByCustomer()
    {
        $this->_getResource()->purgeVisitorByCustomer($this);
        return $this;
    }

    /**
     * Retrieve Reports Session instance
     *
     * @return Mage_Reports_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('reports/session');
    }

    /**
     * Calculate count of product index items cache
     *
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function calculate()
    {
        $collection = $this->getCollection()
            ->setCustomerId($this->getCustomerId())
            ->addIndexFilter();

        Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInSiteFilterToCollection($collection);

        $count = $collection->getSize();
        $this->_getSession()->setData($this->_countCacheKey, $count);
        return $this;
    }

    /**
     * Retrieve Exclude Product Ids List for Collection
     *
     * @return array
     */
    public function getExcludeProductIds()
    {
        return [];
    }

    /**
     * Retrieve count of product index items
     *
     * @return int
     */
    public function getCount()
    {
        if (!$this->_countCacheKey) {
            return 0;
        }

        if (!$this->_getSession()->hasData($this->_countCacheKey)) {
            $this->calculate();
        }

        return $this->_getSession()->getData($this->_countCacheKey);
    }

    /**
     * Clean index (visitors)
     *
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function clean()
    {
        $this->_getResource()->clean($this);
        return $this;
    }

    /**
     * Add product ids to current visitor/customer log
     * @param array $productIds
     * @return Mage_Reports_Model_Product_Index_Abstract
     */
    public function registerIds($productIds)
    {
        try {
            $this->_getResource()->registerIds($this, $productIds);
        } catch (Exception $exception) {
            if ($this->hasCustomerId()) {
                $this->updateCustomerFromVisitor();
                $this->_getResource()->registerIds($this, $productIds);
            } else {
                Mage::logException($exception);
            }
        }
        $this->_getSession()->unsData($this->_countCacheKey);
        return $this;
    }
}
