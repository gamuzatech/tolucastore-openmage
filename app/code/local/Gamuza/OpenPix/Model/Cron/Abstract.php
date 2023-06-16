<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Model_Cron_Abstract
{
    public function __construct ()
    {
        Mage::app ()->getTranslator ()->init (Mage_Core_Model_App_Area::AREA_ADMINHTML, true);

        if (!$this->isCli ()) $this->message ('<pre>');

        $this->message ('+ Begin : ' . strftime ('%c'));

        static::_construct ();
    }

    public function _construct () {}

    public function __destruct ()
    {
        $this->message ('= End : ' . strftime ('%c'));

        if (!$this->isCli ()) $this->message ('</pre>');
    }

    protected function getHelper ()
    {
        return Mage::helper ('openpix');
    }

    protected function getStoreConfig ($key)
    {
        return $this->getHelper ()->getStoreConfig ($key);
    }

    protected function isCli ()
    {
        if (!strcmp (php_sapi_name (), 'cli') && empty ($_SERVER ['REMOTE_ADDR']))
        {
            return true;
        }
    }

    protected function message ($text)
    {
        Mage::log ($text, null, Gamuza_OpenPix_Helper_Data::LOG, false);
    }
}

