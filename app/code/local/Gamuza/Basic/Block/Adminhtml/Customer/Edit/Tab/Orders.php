<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml customer orders grid block
 */
class Gamuza_Basic_Block_Adminhtml_Customer_Edit_Tab_Orders
    extends Mage_Adminhtml_Block_Customer_Edit_Tab_Orders
{
    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('status')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            /*
            ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId())
            */
            ->setIsCustomerMode(true)
        ;

        $customerId = Mage::registry('current_customer')->getId();

        if (!Mage::helper('core')->isModuleEnabled('Toluca_PDV'))
        {
            $collection->addFieldToFilter('customer_id', array('eq' => $customerId));
        }
        else
        {
            $collection->addFieldToFilter(
                array('customer_id', 'pdv_customer_id'),
                array(
                    array('eq' => $customerId),
                    array('eq' => $customerId)
                )
            );
        }

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}

