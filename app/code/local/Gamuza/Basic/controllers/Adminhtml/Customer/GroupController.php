<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'Customer' . DS . 'GroupController.php');

/**
 * Customer groups controller
 */
class Gamuza_Basic_Adminhtml_Customer_GroupController
    extends Mage_Adminhtml_Customer_GroupController
{
    /**
     * Create or save customer group.
     */
    public function saveAction()
    {
        $customerGroup = Mage::getModel('customer/group');

        $id = $this->getRequest()->getParam('id');

        if (!is_null($id))
        {
            $customerGroup->load((int)$id);
        }

        $taxClass = (int)$this->getRequest()->getParam('tax_class');

        if ($taxClass)
        {
            try
            {
                $customerGroupCode = (string)$this->getRequest()->getParam('code');

                if (!empty($customerGroupCode))
                {
                    $customerGroup->setCode($customerGroupCode);
                }

                $customerGroupName = (string)$this->getRequest()->getParam('name');

                if (!empty($customerGroupName))
                {
                    $customerGroup->setName($customerGroupName);
                }

                $customerGroup->setTaxClassId($taxClass)->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('customer')->__('The customer group has been saved.'));

                $this->getResponse()->setRedirect($this->getUrl('*/customer_group'));

                return;
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                Mage::getSingleton('adminhtml/session')->setCustomerGroupData($customerGroup->getData());

                $this->getResponse()->setRedirect($this->getUrl('*/customer_group/edit', ['id' => $id]));

                return;
            }
        }
        else
        {
            $this->_forward('new');
        }
    }
}

