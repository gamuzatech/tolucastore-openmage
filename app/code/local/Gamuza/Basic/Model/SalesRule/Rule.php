<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_SalesRule_Rule extends Mage_SalesRule_Model_Rule
{
    protected function _beforeSave ()
    {
        $this->setWeekdayIds (
            is_array ($this->getWeekdayIds ())
            ? implode (',', $this->getWeekdayIds ())
            : null
        );

        return parent::_beforeSave ();
    }

    protected function _afterLoad ()
    {
        $this->setWeekdayIds (
            !empty ($this->getWeekdayIds ())
            ? explode (',', $this->getWeekdayIds ())
            : null
        );

        return parent::_afterLoad ();
    }
}

