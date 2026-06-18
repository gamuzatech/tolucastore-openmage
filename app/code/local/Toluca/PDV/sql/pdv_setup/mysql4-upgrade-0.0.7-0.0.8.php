<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('pdv_setup');
$installer->startSetup ();

/**
 * Sales Order Grid - PDV Customer ID
 */
$this->getConnection ()->addColumn(
    $this->getTable ('sales/order_grid'),
    'pdv_customer_id',
    'INT(11) DEFAULT NULL'
);

$this->getConnection ()->addKey(
    $this->getTable ('sales/order_grid'),
    'pdv_customer_id',
    'pdv_customer_id'
);

$select = $this->getConnection ()->select ();

$select->join(
    array ('order' => $this->getTable ('sales/order')),
    'order.entity_id = grid.entity_id',
    array ('pdv_customer_id')
);

$this->getConnection()->query(
    $select->crossUpdateFromSelect(
        array ('grid' => $this->getTable ('sales/order_grid'))
    )
);

$installer->endSetup ();

