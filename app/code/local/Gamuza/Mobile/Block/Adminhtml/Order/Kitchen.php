<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Block_Adminhtml_Order_Kitchen
    extends Gamuza_Mobile_Block_Adminhtml_Order_Draft
{
    public function getOrderTitle ()
    {
        $result = parent::getOrderTitle ();

        $pdvCardId = $this->getOrder ()->getPdvCardId ();
        $pdvTableId = $this->getOrder ()->getPdvTableId ();

        if ($pdvCardId)
        {
            $result = Mage::helper ('basic')->__('Card #%s', $pdvCardId);
        }
        else if ($pdvTableId)
        {
            $result = Mage::helper ('basic')->__('Table #%s', $pdvTableId);
        }

        return $result;
    }

    public function getShippingMethod ()
    {
        $result = null;

        $shippingMethod = explode ('_', $this->getOrder ()->getShippingMethod ());

        if (count ($shippingMethod) > 0)
        {
            $carrier = $shippingMethod [0];

            switch ($carrier)
            {
                case 'eatin':
                {
                    $result = Mage::helper ('basic')->__('Eat In');

                    break;
                }
                case 'pickup':
                {
                    $result = Mage::helper ('basic')->__('Pickup');

                    break;
                }
                default:
                {
                    $result = Mage::helper ('basic')->__('Shipping');

                    break;
                }
            }
        }

        return $result;
    }

    public function getAllVisibleItems ()
    {
        $result = array ();

        foreach ($this->getOrder ()->getAllVisibleItems () as $item)
        {
            if ($this->getItem () && $this->getItem ()->getId () != $item->getId ())
            {
                continue; // skip
            }

            $result [] = $item;
        }

        return $result;
    }
}

