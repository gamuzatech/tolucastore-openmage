<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer supplier attribute source
 */
class Gamuza_Basic_Model_Eav_Entity_Attribute_Source_Customer_Abstract
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_groupId = null;

    public function getAllOptions()
    {
        if (empty($this->_options))
        {
            $this->_options = array(array(
                'value' => '', 'label' => ''
            ));

            $collection = Mage::getModel('customer/customer')->getCollection() 
                ->addNameToSelect()
                ->addAttributeToSelect('taxvat')
            ;

            if (!empty ($this->_groupId))
            {
                $collection->addFieldToFilter('group_id', array('eq' => $this->_groupId));
            }

            foreach ($collection as $customer)
            {
                $this->_options[] = array(
                    'value' => $customer->getId(),
                    'label' => sprintf('%s - %s - %s',
                        $customer->getId(),
                        $customer->getName(),
                        $customer->getTaxvat()
                    )
                );
            }
        }

        return $this->_options;
    }
}

