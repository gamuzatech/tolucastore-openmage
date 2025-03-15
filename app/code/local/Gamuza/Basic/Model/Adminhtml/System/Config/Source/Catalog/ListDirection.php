<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Catalog_ListDirection
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            array(
                'label' => Mage::helper('basic')->__('Set Ascending Direction'),
                'value' => 'asc',
            ),
            array(
                'label' => Mage::helper('basic')->__('Set Descending Direction'),
                'value' => 'desc',
            ),
        );

        return $options;
    }
}

