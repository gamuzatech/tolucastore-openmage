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

    /**
     * Controller pre-dispatch method
     *
     * @return $this
     */
    public function preDispatch ()
    {
        if ($this->_isRedirect ())
        {
            $this->setFlag ('redirect', self::FLAG_NO_PRE_DISPATCH, true);
        }

        return parent::preDispatch ();
    }

    public function redirectAction ()
    {
        $customerId = $this->getRequest ()->getParam ('customer_id');

        $isLoggedIn = Mage::getSingleton ('admin/session')->isLoggedIn ();

        if (intval ($customerId) > 0 && $isLoggedIn)
        {
            return $this->_redirect ('adminhtml/customer/edit', array ('id' => $customerId));
        }

        $homeUrl = Mage::helper ('core/url')->getHomeUrl ();

        return $this->_redirectUrl ($homeUrl);
    }

    protected function _isAllowed ()
    {
        if ($this->_isRedirect ())
        {
            return true;
        }

        return parent::_isAllowed ();
    }

    protected function _isRedirect ()
    {
        $request = $this->getRequest ();

        $result = $request->getRouteName () === 'adminhtml'
            && $request->getControllerName () === 'customer_customer'
            && $request->getActionName () === 'redirect';

        return $result;
    }
}

