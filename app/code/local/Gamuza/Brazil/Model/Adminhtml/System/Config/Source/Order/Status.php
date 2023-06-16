<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order Statuses source model
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Order_Status
    extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{
    // set null to enable all possible
    protected $_stateStatuses = null;
}

