<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup ('mobile_setup');
$installer->startSetup ();

/**
 * Quote & Order
 */
$entities = array(
    'quote',
    'order',
);

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'usigned'  => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_IS_APP, $options);
}

$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

foreach ($entities as $entity)
{
    $installer->addAttribute ($entity, Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_CUSTOMER_INFO_CODE, $options);
    $installer->addAttribute ($entity, Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_STORE_INFO_CODE, $options);
}

/**
 * Order Table
 */
$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'usigned'  => true,
    'nullable' => false,
    'visible'  => true,
    'required' => false,
);

$installer->addAttribute (Mage_Sales_Model_Order::ENTITY, Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_IS_PRINTED, $options);

$installer->getConnection ()->addColumn (
    $installer->getTable ('sales/order'),
    Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_CUSTOMER_STARS,
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length'   => 1,
        'unsigned' => true,
        'nullable' => true,
        'comment'  => 'Customer Stars',
    )
);

/**
 * Order Grid
 */
$installer->getConnection ()->addColumn(
    $installer->getTable ('sales/order_grid'),
    'customer_stars',
    'tinyint(1) UNSIGNED DEFAULT NULL'
);

$this->getConnection ()->addKey(
    $this->getTable ('sales/order_grid'),
    'customer_stars',
    'customer_stars'
);

$select = $this->getConnection ()->select ();

$select->join(
    array ('order' => $this->getTable ('sales/order')),
    'order.entity_id = grid.entity_id',
    array ('customer_stars')
);

$this->getConnection()->query(
    $select->crossUpdateFromSelect(
        array ('grid' => $this->getTable ('sales/order_grid'))
    )
);

/**
 * Order Status History
 */
$installer->getConnection ()->addColumn(
    $installer->getTable ('sales/order_status_history'),
    'is_customer_rating',
    'tinyint(1) UNSIGNED DEFAULT NULL'
);

$installer->getConnection ()->addColumn(
    $installer->getTable ('sales/order_status_history'),
    'is_customer_pushed',
    'tinyint(1) UNSIGNED DEFAULT 0'
);

$installer->endSetup ();

