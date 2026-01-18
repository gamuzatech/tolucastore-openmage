<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Block for Deferred payment method form
 */
class Gamuza_Basic_Block_Payment_Form_Deferred extends Mage_Payment_Block_Form_Cashondelivery
{
    /**
     * Block construction. Set block template.
     */
    protected function _construct ()
    {
        parent::_construct ();

        $this->setTemplate ('gamuza/basic/payment/form/deferred.phtml');
    }

    /**
     * Retrieve availables installments qtys
     *
     * @return array
     */
    public function getInstallmentsAvailableQtys ()
    {
        return $this->getMethod ()->_getInstallmentsAvailableQtys ();
    }

    /**
     * Retrieve availables interval days
     *
     * @return array
     */
    public function getIntervalAvailableDays ()
    {
        return $this->getMethod ()->_getIntervalAvailableDays ();
    }

    /**
     * Retrieve availables first due days
     *
     * @return array
     */
    public function getFirstDueAvailableDays()
    {
        return $this->getMethod ()->_getFirstDueAvailableDays ();
    }
}

