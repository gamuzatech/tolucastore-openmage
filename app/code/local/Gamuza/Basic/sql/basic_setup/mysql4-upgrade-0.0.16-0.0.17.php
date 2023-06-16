<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Customer_Model_Entity_Setup ('basic_setup');
$installer->startSetup ();

function updateCustomerGroupTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_ATTRIBUTE_NAME, array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 255,
            'nullable' => false,
            'comment'  => 'Customer Group Name',
        ));
    $installer->getConnection ()
        ->addColumn ($table, Gamuza_Basic_Helper_Data::CUSTOMER_GROUP_ATTRIBUTE_IS_SYSTEM, array(
            'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'length'   => 1,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Customer Group Is System',
        ));
}

updateCustomerGroupTable ($installer, 'customer_group', 'Customer Group');

$installer->endSetup ();

