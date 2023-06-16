<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order model
 */
class Gamuza_Basic_Model_Sales_Order extends Mage_Sales_Model_Order
{
    /**
     * Order statuses
     */
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_PREPARING = 'preparing';
    const STATUS_SHIPPED   = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_REFUNDED  = 'refunded';
    const STATUS_CANCELED  = 'canceled';

    public function canPrepare ()
    {
        $collection = Mage::getModel ('sales/order_status_history')->getCollection ()
            ->addFieldToFilter ('parent_id', array ('eq' => $this->getId ()))
            ->addFieldToFilter ('status',    array ('eq' => self::STATUS_PREPARING))
        ;

        if ((!strcmp ($this->getState (), self::STATE_NEW) && !strcmp ($this->getStatus (), self::STATUS_PENDING))
            || (!strcmp ($this->getState (), self::STATE_PROCESSING) && !strcmp ($this->getStatus (), self::STATUS_PAID) && !$collection->getSize ())
            || (!strcmp ($this->getState (), self::STATE_COMPLETE) && !strcmp ($this->getStatus (), self::STATUS_PAID) && $this->getIsVirtual ()))
        {
            return true;
        }
    }

    public function canDeliver ()
    {
        if (!strcmp ($this->getState (), self::STATE_COMPLETE)
            && in_array ($this->getStatus (), array(
                self::STATUS_PAID, self::STATUS_SHIPPED
            )))
        {
            return true;
        }
    }

    public function hasServices ()
    {
        $collection = Mage::getModel ('basic/order_service')->getCollection ()
            ->addFieldToFilter ('order_id', array ('eq' => $this->getId ()))
        ;

        return $collection->getSize () > 0;
    }
}

