<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Catalog' . DS . 'Product' . DS . 'Action' . DS . 'AttributeController.php');

/**
 * Adminhtml catalog product action attribute update controller
 */
class Gamuza_Basic_Adminhtml_Catalog_Product_Action_AttributeController
    extends Mage_Adminhtml_Catalog_Product_Action_AttributeController
{
    /**
     * Update product attributes
     */
    public function saveAction()
    {
        $attributesData = $this->getRequest()->getParam('attributes', array());

        foreach ($attributesData as $code => $value)
        {
            if (str_starts_with($code, 'brazil_') && empty($value))
            {
                unset($attributesData[$code]);
            }
        }

        $this->getRequest()->setParam('attributes', $attributesData);

        return parent::saveAction();
    }
}

