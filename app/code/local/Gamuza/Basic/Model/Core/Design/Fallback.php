<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Core_Design_Fallback extends Mage_Core_Model_Design_Fallback
{
   /**
     * Get fallback scheme when inheritance is not defined (backward compatibility)
     *
     * @return array
     */
    protected function _getLegacyFallbackScheme()
    {
        $result = array(
            array(),
            array('_theme' => $this->_getDefaultTheme()),
            array('_theme' => $this->_getFallbackTheme()),
            array('_theme' => Mage_Core_Model_Design_Package::DEFAULT_THEME),
        );

        return $result;
    }

    /**
     * Admin theme getter
     * @return string
     */
    public function _getAdminTheme ()
    {
        return Mage::getStoreConfig ('design/theme/admin');
    }

    /**
     * Default theme getter
     * @return string
     */
    protected function _getDefaultTheme()
    {
        $theme = null;

        if (Mage::app ()->getStore ()->isAdmin ())
        {
            $theme = $this->_getAdminTheme ();
        }
        else
        {
            $theme = $this->getStore()->getConfig('design/theme/default');
        }

        return $theme;
    }

    /**
     * Fallback theme getter
     * @return string
     */
    protected function _getFallbackTheme()
    {
        return $this->getStore()->getConfig('design/theme/fallback');
    }
}

