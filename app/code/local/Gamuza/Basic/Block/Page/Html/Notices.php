<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Html notices block
 */
class Gamuza_Basic_Block_Page_Html_Notices extends Mage_Page_Block_Html_Notices
{
    public function getAppLink()
    {
        return Mage::getStoreConfig('design/head/app_notice_url');
    }
}

