<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Source export entity model
 */
class Gamuza_Basic_Model_ImportExport_Source_Export_Entity extends Mage_ImportExport_Model_Source_Export_Entity
{
    /**
     * Prepare and return array of export entities ids and their names
     *
     * @return array
     */
    public function toOptionArray()
    {
        return Gamuza_Basic_Model_ImportExport_Config::getModelsComboOptions(
            Mage_ImportExport_Model_Export::CONFIG_KEY_ENTITIES, true
        );
    }
}

