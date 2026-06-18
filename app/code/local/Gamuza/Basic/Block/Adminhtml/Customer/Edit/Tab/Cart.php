<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml customer orders grid block
 */
class Gamuza_Basic_Block_Adminhtml_Customer_Edit_Tab_Cart
    extends Mage_Adminhtml_Block_Customer_Edit_Tab_Cart
{
    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $customer = Mage::registry('current_customer');

        $storeIds = Mage::app()->getWebsite($this->getWebsiteId())->getStoreIds();

        $quote = Mage::getModel('sales/quote')
            ->setSharedStoreIds($storeIds)
            /*
            ->loadByCustomer($customer)
            */
        ;

        if (!Mage::helper('core')->isModuleEnabled('Toluca_PDV'))
        {
            $quote->loadByCustomer($customer);
        }
        else
        {
            $quote = Mage::getModel('sales/quote')->getCollection()
                ->addFieldToFilter(
                    array('customer_id', 'pdv_customer_id'),
                    array(
                        array('eq' => $customer->getId()),
                        array('eq' => $customer->getId())
                    )
                )
                ->getFirstItem()
            ;
        }

        if ($quote)
        {
            $collection = $quote->getItemsCollection(false);
        }
        else
        {
            $collection = new Varien_Data_Collection();
        }

        $collection->addFieldToFilter('parent_item_id', array('null' => true));

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}

