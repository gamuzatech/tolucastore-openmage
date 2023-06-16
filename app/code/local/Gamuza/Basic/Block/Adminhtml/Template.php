<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml abstract block
 */
class Gamuza_Basic_Block_Adminhtml_Template extends Mage_Adminhtml_Block_Template
{
    /**
     * Retrieve url of skins file
     *
     * @param   string $file path to file in skin
     * @param   array $params
     * @return  string
     */
    public function getSkinUrl($file = null, array $params = array())
    {
        $file = str_replace('favicon.ico', 'favicon.png', $file);

        return parent::getSkinUrl($file, $params);
    }
}

