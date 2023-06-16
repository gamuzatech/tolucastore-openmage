<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

/**
 * Sales Order Grid - State
 */
$this->getConnection ()->addColumn(
    $this->getTable ('sales/order_grid'),
    'state',
    'varchar(255) DEFAULT NULL AFTER entity_id'
);

$this->getConnection ()->addKey(
    $this->getTable ('sales/order_grid'),
    'state',
    'state'
);

$select = $this->getConnection ()->select ();

$select->join(
    array ('order' => $this->getTable ('sales/order')),
    'order.entity_id = grid.entity_id',
    array('state')
);

$this->getConnection()->query(
    $select->crossUpdateFromSelect(
        array ('grid' => $this->getTable ('sales/order_grid'))
    )
);

$installer->endSetup ();

