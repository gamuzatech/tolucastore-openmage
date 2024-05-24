<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Ncm
    extends Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
{
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if ($this->_options === null)
        {
            $this->_options = array (
                array ('value' => 0, 'label' => Mage::helper ('core')->__('-- Please Select --')),
            );

            if (!Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_ACTIVE))
            {
                return $this->_options;
            }

            $collection = Mage::getModel ('brazil/ibpt')->getCollection ()
                ->addFieldToFilter ('type', array ('eq' => Gamuza_Brazil_Helper_Data::IBPT_TYPE_NCM))
            ;

            foreach ($collection as $ibpt)
            {
                $this->_options [] = array ('value' => $ibpt->getCode (), 'label' => sprintf ('%s %s', $ibpt->getCode (), $ibpt->getDescription ()));
            }
        }

        return $this->_options;
    }
}

