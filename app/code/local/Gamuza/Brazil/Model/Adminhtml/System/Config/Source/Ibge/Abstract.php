<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibge_Abstract
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array (
            array ('value' => 0, 'label' => Mage::helper ('core')->__('-- Please Select --')),
        );

        $collection = $this->_getCollection ();

        foreach ($collection as $item)
        {
            $result [] = array ('value' => $item->getCode (), 'label' => sprintf ('%s %s', $item->getCode (), $item->getDescription ()));
        }

        return $result;
    }
}

