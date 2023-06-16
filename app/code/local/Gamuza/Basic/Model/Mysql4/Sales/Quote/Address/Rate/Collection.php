<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Quote addresses shiping rates collection
 */
class Gamuza_Basic_Model_Mysql4_Sales_Quote_Address_Rate_Collection
    extends Mage_Sales_Model_Resource_Quote_Address_Rate_Collection
{
    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);

        // Count doesn't work with group by columns keep the group by 
        if(count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0)
        {
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->distinct(true);

            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);

            $countSelect->columns(sprintf("COUNT(DISTINCT %s)", implode(",", $group)));
        }
        else
        {
            $countSelect->columns('COUNT(*)');
        }

        return $countSelect;
    }
}

