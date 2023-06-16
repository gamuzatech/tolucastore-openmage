<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Core_Design_Package extends Mage_Core_Model_Design_Package
{
    /**
     * @param array $params
     * @return string
     */
    public function getSkinBaseUrl(array $params = array())
    {
        $params['_type'] = 'skin';

        $this->updateParamDefaults($params);

        $skinUrl = Mage::app ()
            ->getStore ($this->getStore ())
            ->getBaseUrl (Mage_Core_Model_Store::URL_TYPE_SKIN, $params['_secure'] ?? null)
        ;

        $baseUrl = sprintf ('%s%s/%s/%s/', $skinUrl,
            $params['_area'], $params['_package'], $params['_theme']
        );

        return $baseUrl;
    }
}

