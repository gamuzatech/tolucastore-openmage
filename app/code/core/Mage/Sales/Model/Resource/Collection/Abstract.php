<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Sales
 */

/**
 * Flat sales abstract collection
 *
 * @package    Mage_Sales
 */
abstract class Mage_Sales_Model_Resource_Collection_Abstract extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Check if $attribute is Mage_Eav_Model_Entity_Attribute and convert to string field name
     *
     * @param string|Mage_Eav_Model_Entity_Attribute $attribute
     * @return string
     */
    protected function _attributeToField($attribute)
    {
        $field = false;
        if (is_string($attribute)) {
            $field = $attribute;
        } elseif ($attribute instanceof Mage_Eav_Model_Entity_Attribute) {
            $field = $attribute->getAttributeCode();
        }
        if (!$field) {
            Mage::throwException(Mage::helper('sales')->__('Cannot determine the field name.'));
        }
        return $field;
    }

    /**
     * Add attribute to select result set.
     * Backward compatibility with EAV collection
     *
     * @param string $attribute
     * @return $this
     */
    public function addAttributeToSelect($attribute)
    {
        $this->addFieldToSelect($this->_attributeToField($attribute));
        return $this;
    }

    /**
     * Specify collection select filter by attribute value
     * Backward compatibility with EAV collection
     *
     * @param string|Mage_Eav_Model_Entity_Attribute $attribute
     * @param array|int|string|null $condition
     * @return $this
     */
    public function addAttributeToFilter($attribute, $condition = null)
    {
        $this->addFieldToFilter($this->_attributeToField($attribute), $condition);
        return $this;
    }

    /**
     * Specify collection select order by attribute value
     * Backward compatibility with EAV collection
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function addAttributeToSort($attribute, $dir = 'asc')
    {
        $this->addOrder($this->_attributeToField($attribute), $dir);
        return $this;
    }

    /**
     * Set collection page start and records to show
     * Backward compatibility with EAV collection
     *
     * @param int $pageNum
     * @param int $pageSize
     * @return $this
     */
    public function setPage($pageNum, $pageSize)
    {
        $this->setCurPage($pageNum)
            ->setPageSize($pageSize);
        return $this;
    }

    /**
     * Create all ids retrieving select with limitation
     * Backward compatibility with EAV collection
     *
     * @param int $limit
     * @param int $offset
     * @return Varien_Db_Select
     */
    protected function _getAllIdsSelect($limit = null, $offset = null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');
        $idsSelect->limit($limit, $offset);
        return $idsSelect;
    }

    /**
     * Retrieve all ids for collection
     * Backward compatibility with EAV collection
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol(
            $this->_getAllIdsSelect($limit, $offset),
            $this->_bindParams,
        );
    }

    /**
     * Backward compatibility with EAV collection
     *
     * @todo implement join functionality if necessary
     *
     * @param string $alias
     * @param string $attribute
     * @param string $bind
     * @param string $filter
     * @param string $joinType
     * @param int $storeId
     * @return $this
     */
    public function joinAttribute($alias, $attribute, $bind, $filter = null, $joinType = 'inner', $storeId = null)
    {
        return $this;
    }
}
