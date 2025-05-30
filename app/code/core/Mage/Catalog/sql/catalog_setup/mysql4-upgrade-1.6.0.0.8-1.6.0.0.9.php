<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Catalog
 */

$installFile = __DIR__ . DS . 'upgrade-1.6.0.0.8-1.6.0.0.9.php';
if (file_exists($installFile)) {
    include $installFile;
}

/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;

/** @var Varien_Db_Adapter_Pdo_Mysql $connection */
$connection = $installer->getConnection();

$memoryTables = [
    'catalog/category_anchor_indexer_tmp',
    'catalog/category_anchor_products_indexer_tmp',
    'catalog/category_product_enabled_indexer_tmp',
    'catalog/category_product_indexer_tmp',
    'catalog/product_eav_decimal_indexer_tmp',
    'catalog/product_eav_indexer_tmp',
    'catalog/product_price_indexer_cfg_option_aggregate_tmp',
    'catalog/product_price_indexer_cfg_option_tmp',
    'catalog/product_price_indexer_final_tmp',
    'catalog/product_price_indexer_option_aggregate_tmp',
    'catalog/product_price_indexer_option_tmp',
    'catalog/product_price_indexer_tmp',
];

foreach ($memoryTables as $table) {
    $connection->changeTableEngine($installer->getTable($table), Varien_Db_Adapter_Pdo_Mysql::ENGINE_MEMORY);
}
