<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Ibge_Region config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibge_Region
    extends Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibge_Abstract
{
    public function _getCollection ()
    {
        $collection = Mage::getModel ('brazil/region')->getCollection ();

        $countryId = Mage::getStoreConfig (Mage_Core_Model_Locale::XML_PATH_DEFAULT_COUNTRY);

        $collection->getSelect ()
            ->joinLeft(
                array ('dcr' => Mage::getSingleton ('core/resource')->getTablename ('directory/country_region')),
                sprintf ("main_table.acronym = dcr.code AND dcr.country_id = '%s'", $countryId),
                array(
                    'name' => "CONCAT(dcr.default_name, ' ', main_table.acronym)",
                )
            )
        ;

        return $collection;
    }
}

