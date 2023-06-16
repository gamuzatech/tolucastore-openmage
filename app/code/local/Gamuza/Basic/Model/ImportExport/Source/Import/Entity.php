<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Source import entity model
 */
class Gamuza_Basic_Model_ImportExport_Source_Import_Entity extends Mage_ImportExport_Model_Source_Import_Entity
{
    /**
     * Prepare and return array of import entities ids and their names
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        $entities = Mage_ImportExport_Model_Import::CONFIG_KEY_ENTITIES;
        $comboOptions = Gamuza_Basic_Model_ImportExport_Config::getModelsComboOptions($entities);

        foreach ($comboOptions as $option)
        {
           $options[] = $option;
        }

        return $options;
    }
}

