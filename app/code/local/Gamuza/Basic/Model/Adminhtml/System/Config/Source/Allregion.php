<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Allregion
{
    public function toOptionArray ()
    {
        $countryId = Mage::getStoreConfig ('general/country/default');

        $collection = Mage::getModel ('directory/region')->getCollection ()
            ->addCountryFilter ($countryId)
        ;

        $result = array ('0' => Mage::helper ('adminhtml')->__('All'));

        foreach ($collection as $region)
        {
            $result [$region->getId ()] = sprintf ('%s ( %s )', $region->getName (), $region->getId ());
        }

        return $result;
    }
}

