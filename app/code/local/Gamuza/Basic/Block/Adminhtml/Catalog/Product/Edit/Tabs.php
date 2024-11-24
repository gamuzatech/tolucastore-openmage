<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * admin product edit tabs
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tabs
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    protected function _prepareLayout ()
    {
        $result = parent::_prepareLayout ();

        if (Mage::registry('current_product')->getId())
        {
            if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view'))
            {
                $this->addTab('orders', array(
                    'label' => Mage::helper('catalog')->__('Orders'),
                    'url'   => $this->getUrl('*/*/orders', ['_current' => true]),
                    'class' => 'ajax',
                ));
            }

            if (Mage::helper('core')->isModuleOutputEnabled('Mage_Wishlist'))
            {
                $this->addTab('wishlist', array(
                    'label' => Mage::helper('catalog')->__('Wishlist'),
                    'url'   => $this->getUrl('*/*/wishlist', ['_current' => true]),
                    'class' => 'ajax',
                ));
            }
        }

        return $result;
    }
}

