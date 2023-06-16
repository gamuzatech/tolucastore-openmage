<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('basic_setup');
$installer->startSetup ();

function updateCatalogProductOptionTable ($installer, $model, $comment)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, 'max_length', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'length'   => 11,
            'unsigned' => true,
            'nullable' => false,
            'comment'  => 'Max Length',
        ))
    ;
}

updateCatalogProductOptionTable ($installer, 'catalog_product_option', 'Catalog Product Option');

$installer->endSetup ();

