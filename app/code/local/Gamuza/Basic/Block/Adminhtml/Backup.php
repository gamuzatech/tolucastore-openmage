<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml backup page content block
 */
class Gamuza_Basic_Block_Adminhtml_Backup extends Mage_Adminhtml_Block_Backup
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->unsetChild('createSnapshotButton');
    }
}

