<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Core
 */

/**
 * Filter data collector
 *
 * Model for multi-filtering all data which set to models
 * Example:
 * <code>
 * /** @var Mage_Core_Model_Input_Filter $filter {@*}
 * $filter = Mage::getModel('core/input_filter');
 * $filter->setFilters(array(
 *      'list_values' => array(
 *          'children_filters' => array( //filters will applied to all children
 *              array(
 *                  'zend' => 'StringToUpper',
 *                  'args' => array('encoding' => 'utf-8')),
 *              array('zend' => 'StripTags')
 *          )
 *      ),
 *      'list_values_with_name' => array(
 *          'children_filters' => array(
 *              'item1' => array(
 *                  array(
 *                      'zend' => 'StringToUpper',
 *                      'args' => array('encoding' => 'utf-8'))),
 *              'item2' => array(
 *                  array('model' => 'core/input_filter_maliciousCode')
 *              ),
 *              'item3' => array(
 *                  array(
 *                      'helper' => 'core',
 *                      'method' => 'stripTags',
 *                      'args' => array('<p> <div>', true))
 *              )
 *          )
 *      )
 *  ));
 *  $filter->addFilter('name2', new Zend_Filter_Alnum());
 *  $filter->addFilter('name1',
 *      array(
 *          'zend' => 'StringToUpper',
 *          'args' => array('encoding' => 'utf-8')));
 *  $filter->addFilter('name1', array('zend' => 'StripTags'), Zend_Filter::CHAIN_PREPEND);
 *  $filter->addFilters(protected $_filtersToAdd = array(
 *      'list_values_with_name' => array(
 *          'children_filters' => array(
 *              'deep_list' => array(
 *                  'children_filters' => array(
 *                      'sub1' => array(
 *                          array(
 *                              'zend' => 'StringToLower',
 *                              'args' => array('encoding' => 'utf-8'))),
 *                      'sub2' => array(array('zend' => 'Int'))
 *                  )
 *              )
 *          )
 *      )
 *  ));
 *  $filter->filter(array(
 *      'name1' => 'some <b>string</b>',
 *      'name2' => '888 555',
 *      'list_values' => array(
 *          'some <b>string2</b>',
 *          'some <p>string3</p>',
 *      ),
 *      'list_values_with_name' => array(
 *          'item1' => 'some <b onclick="alert(\'2\')">string4</b>',
 *         'item2' => 'some <b onclick="alert(\'1\')">string5</b>',
 *          'item3' => 'some <p>string5</p> <b>bold</b> <div>div</div>',
 *          'deep_list' => array(
 *              'sub1' => 'toLowString',
 *              'sub2' => '5 TO INT',
 *          )
 *      )
 *  ));
 * </code>
 *
 * @package    Mage_Core
 * @see Mage_Core_Model_Input_FilterTest See this class for manual
 */
class Mage_Core_Model_Input_Filter implements Zend_Filter_Interface
{
    /**
     * Filters data collectors
     *
     * @var array
     */
    protected $_filters = [];

    /**
     * Add filter
     *
     * @param string|Zend_Filter_Interface $name
     * @param string|Zend_Filter_Interface $filter
     * @param string $placement
     * @return $this
     */
    public function addFilter($name, $filter, $placement = Zend_Filter::CHAIN_APPEND)
    {
        if ($placement == Zend_Filter::CHAIN_PREPEND) {
            array_unshift($this->_filters[$name], $filter);
        } else {
            $this->_filters[$name][] = $filter;
        }
        return $this;
    }

    /**
     * Add a filter to the end of the chain
     *
     * @return $this
     */
    public function appendFilter(Zend_Filter_Interface $filter)
    {
        return $this->addFilter($filter, Zend_Filter::CHAIN_APPEND);
    }

    /**
     * Add a filter to the start of the chain
     *
     * @param  array|Zend_Filter_Interface $filter
     * @return $this
     */
    public function prependFilter($filter)
    {
        return $this->addFilter($filter, Zend_Filter::CHAIN_PREPEND);
    }

    /**
     * Add filters
     *
     * Filters data must be has view as
     *      array(
     *          'key1' => $filters,
     *          'key2' => array( ... ), //array filters data
     *          'key2' => $filters
     *      )
     *
     * @return $this
     */
    public function addFilters(array $filters)
    {
        $this->_filters = array_merge_recursive($this->_filters, $filters);
        return $this;
    }

    /**
     * Set filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->_filters = $filters;
        return $this;
    }

    /**
     * Get filters
     *
     * @param string|null $name     Get filter for selected name
     * @return array
     */
    public function getFilters($name = null)
    {
        if ($name === null) {
            return $this->_filters;
        } else {
            return $this->_filters[$name] ?? null;
        }
    }

    /**
     * Filter data
     *
     * @param array $data
     * @return array    Return filtered data
     */
    public function filter($data)
    {
        return $this->_filter($data);
    }

    /**
     * Recursive filtering
     *
     * @param array|null $filters
     * @param bool $isFilterListSimple
     * @param-out array $filters
     * @return array
     * @throws Exception    Exception when filter is not found or not instance of defined instances
     */
    protected function _filter(array $data, &$filters = null, $isFilterListSimple = false)
    {
        if ($filters === null) {
            $filters = &$this->_filters;
        }
        foreach ($data as $key => $value) {
            if (!$isFilterListSimple && !empty($filters[$key])) {
                $itemFilters = $filters[$key];
            } elseif ($isFilterListSimple && !empty($filters)) {
                $itemFilters = $filters;
            } else {
                continue;
            }

            if (!$isFilterListSimple && is_array($value) && isset($filters[$key]['children_filters'])) {
                $isChildrenFilterListSimple = is_numeric(implode('', array_keys($filters[$key]['children_filters'])));
                $value = $this->_filter($value, $filters[$key]['children_filters'], $isChildrenFilterListSimple);
            } else {
                foreach ($itemFilters as $filterData) {
                    if ($zendFilter = $this->_getZendFilter($filterData)) {
                        $value = $zendFilter->filter($value);
                    } elseif ($filtrationHelper = $this->_getFiltrationHelper($filterData)) {
                        $value = $this->_applyFiltrationWithHelper($value, $filtrationHelper, $filterData);
                    }
                }
            }
            $data[$key] = $value;
        }
        return $data;
    }

    /**
     * Call specified helper method for $value filtration
     *
     * @param mixed $value
     * @return mixed
     */
    protected function _applyFiltrationWithHelper($value, Mage_Core_Helper_Abstract $helper, array $filterData)
    {
        if (!isset($filterData['method']) || empty($filterData['method'])) {
            throw new Exception('Helper filtration method is not set');
        }
        if (!isset($filterData['args']) || empty($filterData['args'])) {
            $filterData['args'] = [];
        }
        $filterData['args'] = [-100 => $value] + $filterData['args'];
        // apply filter
        $value = call_user_func_array([$helper, $filterData['method']], $filterData['args']);
        return $value;
    }

    /**
     * Try to create Magento helper for filtration based on $filterData. Return false on failure
     *
     * @param array $filterData
     * @return Mage_Core_Helper_Abstract
     * @throws Exception
     */
    protected function _getFiltrationHelper($filterData)
    {
        $helper = false;
        if (isset($filterData['helper'])) {
            $helper = $filterData['helper'];
            if (is_string($helper)) {
                $helper = Mage::helper($helper);
            }
            if (!($helper instanceof Mage_Core_Helper_Abstract)) {
                throw new Exception("Filter '{$filterData['helper']}' not found");
            }
        }
        return $helper;
    }

    /**
     * Try to create Zend filter based on $filterData. Return false on failure
     *
     * @param Zend_Filter_Interface|array $filterData
     * @return false|Zend_Filter_Interface
     */
    protected function _getZendFilter($filterData)
    {
        $zendFilter = false;
        if ($filterData instanceof Zend_Filter_Interface) {
            $zendFilter = $filterData;
        } elseif (isset($filterData['model'])) {
            $zendFilter = $this->_createCustomZendFilter($filterData);
        } elseif (isset($filterData['zend'])) {
            $zendFilter = $this->_createNativeZendFilter($filterData);
        }
        return $zendFilter;
    }

    /**
     * Get Magento filters
     *
     * @param array $filterData
     * @return Zend_Filter_Interface
     * @throws Exception
     */
    protected function _createCustomZendFilter($filterData)
    {
        $filter = $filterData['model'];
        if (!isset($filterData['args'])) {
            $filterData['args'] = null;
        } else {
            //use only first element because Mage factory cannot get more
            $filterData['args'] = $filterData['args'][0];
        }
        if (is_string($filterData['model'])) {
            $filter = Mage::getModel($filterData['model'], $filterData['args']);
        }
        if (!($filter instanceof Zend_Filter_Interface)) {
            throw new Exception('Filter is not instance of Zend_Filter_Interface');
        }
        return $filter;
    }

    /**
     * Get native Zend_Filter
     *
     * @param array $filterData
     * @return Zend_Filter_Interface
     * @throws Exception
     */
    protected function _createNativeZendFilter($filterData)
    {
        $filter = $filterData['zend'];
        if (is_string($filter)) {
            $class = new ReflectionClass('Zend_Filter_' . $filter);
            if ($class->implementsInterface('Zend_Filter_Interface')) {
                if (isset($filterData['args']) && $class->hasMethod('__construct')) {
                    $filter = $class->newInstanceArgs($filterData['args']);
                } else {
                    $filter = $class->newInstance();
                }
            } else {
                throw new Exception('Filter is not instance of Zend_Filter_Interface');
            }
        }
        return $filter;
    }
}
