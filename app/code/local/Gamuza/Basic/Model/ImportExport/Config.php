<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * ImportExport config model
 */
class Gamuza_Basic_Model_ImportExport_Config extends Mage_ImportExport_Model_Config
{
    /**
     * Get model params as combo-box options.
     *
     * @static
     * @param string $configKey
     * @param boolean $withEmpty OPTIONAL Include 'Please Select' option or not
     * @return array
     */
    public static function getModelsComboOptions($configKey, $withEmpty = false)
    {
        $options = array();

        if ($withEmpty)
        {
            $options[] = array('label' => Mage::helper('importexport')->__('-- Please Select --'), 'value' => '');
        }

        foreach (self::getModels($configKey) as $type => $params)
        {
            $options[] = array('value' => $type, 'label' => Mage::helper('importexport')->__($params['label']));
        }

        return $options;
    }
}

