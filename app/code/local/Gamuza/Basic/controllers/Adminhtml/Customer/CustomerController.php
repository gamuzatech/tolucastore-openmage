<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'CustomerController.php');

/**
 * Adminhtml customer customers controller
 */
class Gamuza_Basic_Adminhtml_Customer_CustomerController extends Mage_Adminhtml_CustomerController
{
    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = array ('redirect');

    public function redirectAction ()
    {
        $customerId = $this->getRequest ()->getParam ('customer_id');

        if (intval ($customerId) > 0)
        {
            return $this->_redirect ('adminhtml/customer/edit', array ('id' => $customerId));
        }

        return $this->_redirect ('adminhtml/customer/index');
    }
}

