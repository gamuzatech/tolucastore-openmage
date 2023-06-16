<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Aoe_Scheduler_Adminhtml_Timeline
    extends Aoe_Scheduler_Block_Adminhtml_Timeline
{
    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->removeButton('add_new');
        $this->removeButton('configure');

        return $this;
    }
}

