<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Promo' . DS . 'WidgetController.php');

class Gamuza_Basic_Adminhtml_Promo_WidgetController extends Mage_Adminhtml_Promo_WidgetController
{
    protected function _isAllowed()
    {
        $actionName = $this->getRequest()->getActionName();

        if (!strcmp ($actionName, 'chooser'))
        {
            return true;
        }

        return Mage::getSingleton('admin/session')->isAllowed('promo/catalog');
    }
}

