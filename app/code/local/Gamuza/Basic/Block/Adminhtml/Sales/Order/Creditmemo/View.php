<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml creditmemo view
 */
class Gamuza_Basic_Block_Adminhtml_Sales_Order_Creditmemo_View
    extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_View
{
    public function __construct()
    {
        parent::__construct();

        $this->_removeButton('send_notification');
    }
}

