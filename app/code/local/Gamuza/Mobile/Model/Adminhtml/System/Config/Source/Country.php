<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Adminhtml_System_Config_Source_Country
    extends Mage_Adminhtml_Model_System_Config_Source_Country
{
    protected $_options;

    public function toArray ()
    {
        $result = array ();

        foreach ($this->toOptionArray () as $country)
        {
            $result [$country ['value']] = $country ['label'];
        }

        return $result;
    }

    public function toOptionArray($isMultiselect=false)
    {
        if (!$this->_options)
        {
            $this->_options = Mage::getResourceModel('directory/country_collection')
                ->addFieldToFilter('country_id', 'BR')
                ->loadData()
                ->toOptionArray(false)
            ;
        }

        $options = $this->_options;

        if(!$isMultiselect)
        {
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }
}

