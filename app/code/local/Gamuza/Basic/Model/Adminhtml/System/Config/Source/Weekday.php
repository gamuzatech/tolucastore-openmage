<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Adminhtml_System_Config_Source_weekday
{
    protected $_weekdays = array(
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
        'sun' => 7,
    );

    public function toArray ()
    {
        $options = array ();

        $days = Mage::app ()->getLocale ()->getTranslationList ('days');

        $context = $days ['context'];
        $default = $days ['default'];

        foreach ($days [$context][$default] as $id => $value)
        {
            $index = $this->_weekdays [$id];
            $label = explode ('-', $value);

            $options [$index] = ucfirst ($label [0]);
        }

        ksort ($options);

        return $options;
    }

    public function toOptionArray ()
    {
        $options = array ();

        foreach ($this->toArray () as $id => $value)
        {
            $options [] = array ('value' => $id, 'label' => $value);
        }

        return $options;
    }
}

