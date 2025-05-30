<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/**
 * Abstract API2 class for order address
 *
 * @package    Mage_Sales
 */
abstract class Mage_Sales_Model_Api2_Order_Address_Rest extends Mage_Sales_Model_Api2_Order_Address
{
    /**
     * Parameters in request used in model (usually specified in route mask)
     */
    public const PARAM_ORDER_ID     = 'order_id';
    public const PARAM_ADDRESS_TYPE = 'address_type';

    /**
     * Retrieve order address
     *
     * @return array
     */
    protected function _retrieve()
    {
        /** @var Mage_Sales_Model_Order_Address $address */
        $address = $this->_getCollectionForRetrieve()
            ->addAttributeToFilter('address_type', $this->getRequest()->getParam(self::PARAM_ADDRESS_TYPE))
            ->getFirstItem();
        if (!$address->getId()) {
            $this->_critical(self::RESOURCE_NOT_FOUND);
        }
        return $address->getData();
    }

    /**
     * Retrieve order addresses
     *
     * @return array
     */
    protected function _retrieveCollection()
    {
        $collection = $this->_getCollectionForRetrieve();

        $this->_applyCollectionModifiers($collection);
        $data = $collection->load()->toArray();

        if (count($data['items']) == 0) {
            $this->_critical(self::RESOURCE_NOT_FOUND);
        }

        return $data['items'];
    }

    /**
     * Retrieve collection instances
     *
     * @return Mage_Sales_Model_Resource_Order_Address_Collection
     */
    protected function _getCollectionForRetrieve()
    {
        /** @var Mage_Sales_Model_Resource_Order_Address_Collection $collection */
        $collection = Mage::getResourceModel('sales/order_address_collection');
        $collection->addAttributeToFilter('parent_id', $this->getRequest()->getParam(self::PARAM_ORDER_ID));

        return $collection;
    }
}
