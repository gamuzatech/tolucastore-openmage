<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Mysql4_Quote_Draft
    extends Mage_Sales_Model_Resource_Order_Abstract
{
    protected $_useIncrementId = true;

    protected $_entityTypeForIncrementId = 'quote_draft';

    protected function _construct ()
    {
        $this->_init ('basic/quote_draft', 'entity_id');
    }
}

