<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

/**
 * Sales Order Grid - Shipping Method
 */
$this->getConnection ()->addColumn(
    $this->getTable ('sales/order_grid'),
    'shipping_method',
    'varchar(255) DEFAULT NULL'
);

$this->getConnection ()->addKey(
    $this->getTable ('sales/order_grid'),
    'shipping_method',
    'shipping_method'
);

$select = $this->getConnection ()->select ();

$select->join(
    array ('order' => $this->getTable ('sales/order')),
    'order.entity_id = grid.entity_id',
    array ('shipping_method')
);

$this->getConnection()->query(
    $select->crossUpdateFromSelect(
        array ('grid' => $this->getTable ('sales/order_grid'))
    )
);

$installer->endSetup ();

