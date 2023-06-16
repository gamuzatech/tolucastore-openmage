<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle Option Type Source Model
 */
class Gamuza_Basic_Model_Bundle_Source_Option_Type extends Mage_Bundle_Model_Source_Option_Type
{
    const BUNDLE_OPTIONS_TYPES_PATH = 'global/basic/product/options/bundle/types';

    public function toOptionArray()
    {
        $types = array();

        foreach (Mage::getConfig()->getNode(self::BUNDLE_OPTIONS_TYPES_PATH)->children() as $type)
        {
            $labelPath = self::BUNDLE_OPTIONS_TYPES_PATH . '/' . $type->getName() . '/label';

            $types[] = array(
                'label' => (string) Mage::getConfig()->getNode($labelPath),
                'value' => $type->getName()
            );
        }

        return $types;
    }
}

