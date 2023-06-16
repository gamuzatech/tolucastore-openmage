<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Html page block
 */
class Gamuza_Basic_Block_Page_Html_Header extends Mage_Page_Block_Html_Header
{
    private $_logo = null;

    public function _construct()
    {
        parent::_construct();

        $this->_logo = Mage::helper('basic')->getLogoUrl();
    }

    /**
     * @return string
     */
    public function getLogoSrc()
    {
        if (!empty($this->_logo))
        {
            return $this->_logo;
        }

        return parent::getLogoSrc();
    }

    /**
     * @return string
     */
    public function getLogoSrcSmall()
    {
        if (!empty($this->_logo))
        {
            return $this->_logo;
        }

        return parent::getLogoSrcSmall();
    }
}

