<?php
/**
 * @package     Gamuza_Store
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Customer_Account_Navigation
    extends Mage_Customer_Block_Account_Navigation
{
    protected $_appendLinks = array ();

    public function removeLinkByName ($name)
    {
        unset ($this->_links [$name]);

        return $this;
    }

    public function appendLink ($name, $path, $label, $urlParams = array ())
    {
        $this->_appendLinks [$name] = new Varien_Object (array(
            'name'  => $name,
            'path'  => $path,
            'label' => $label,
            'url'   => $this->getUrl ($path, $urlParams),
        ));

        return $this;
    }

    public function getLinks ()
    {
        return array_merge ($this->_links, $this->_appendLinks);
    }
}

