<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'NotificationController.php');

class Gamuza_Basic_Adminhtml_NotificationController extends Mage_Adminhtml_NotificationController
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
        if (Mage::getSingleton ('admin/session')->isLoggedIn ())
        {
            return $this->_redirect ('adminhtml/notification/index');
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
            && $request->getControllerName () === 'notification'
            && $request->getActionName () === 'redirect';

        return $result;
    }
}

