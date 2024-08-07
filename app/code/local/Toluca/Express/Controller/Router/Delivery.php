<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Express_Controller_Router_Delivery
    extends Mage_Core_Controller_Varien_Router_Standard
{
    public function match (Zend_Controller_Request_Http $request)
    {
        $pathInfo = array_filter (explode ('/', $request->getPathInfo ()));

        if (count ($pathInfo) == 1 && in_array ('delivery', $pathInfo))
        {
            $request->setModuleName ('express')
                ->setControllerName ('category')
                ->setActionName ('view');

            return true;
        }
    }
}

