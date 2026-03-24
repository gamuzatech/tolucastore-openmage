<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Shopping Cart Rules Grid
 */
class Gamuza_Basic_Block_Adminhtml_Promo_Quote_Grid
    extends Mage_Adminhtml_Block_Promo_Quote_Grid
{
    /**
     * Add grid columns
     *
     * @return $this
     */
    protected function _prepareColumns ()
    {
        $this->addColumnAfter ('weekday_ids', array(
            'header'    => Mage::helper ('basic')->__('Weekdays'),
            'align'     => 'left',
            'index'     => 'weekday_ids',
            'type'      => 'options',
            'options'   => Mage::getModel ('basic/adminhtml_system_config_source_weekdays')->toArray (),
        ), 'to_date');

        return parent::_prepareColumns();
    }
}

