<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Catalog
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->updateEntityType('catalog_category', 'additional_attribute_table', 'catalog/eav_attribute');
$installer->updateEntityType('catalog_product', 'additional_attribute_table', 'catalog/eav_attribute');
$installer->updateEntityType('catalog_category', 'entity_attribute_collection', 'catalog/attribute_collection');
$installer->updateEntityType('catalog_product', 'entity_attribute_collection', 'catalog/attribute_collection');
$installer->run("
CREATE TABLE `{$installer->getTable('catalog/eav_attribute')}` (
  `attribute_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `frontend_input_renderer` varchar(255) DEFAULT NULL,
  `is_global` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_searchable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_filterable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_comparable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_visible_on_front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_html_allowed_on_front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_used_for_price_rules` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_filterable_in_search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `used_in_product_listing` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `used_for_sort_by` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_configurable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `apply_to` varchar(255) NOT NULL,
  `is_visible_in_advanced_search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL,
  PRIMARY KEY (`attribute_id`),
  KEY `IDX_USED_FOR_SORT_BY` (`used_for_sort_by`),
  KEY `IDX_USED_IN_PRODUCT_LISTING` (`used_in_product_listing`),
  CONSTRAINT `FK_CATALOG_EAV_ATTRIBUTE_ID` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$fields = [];
$describe = $installer->getConnection()->describeTable($installer->getTable('catalog/eav_attribute'));
foreach ($describe as $columnData) {
    $fields[] = $columnData['COLUMN_NAME'];
}
$stmt = $installer->getConnection()->select()
    ->from($installer->getTable('eav/attribute'), $fields)
    ->where('entity_type_id = ?', $installer->getEntityTypeId('catalog_category'))
    ->orWhere('entity_type_id = ?', $installer->getEntityTypeId('catalog_product'));
$result = $installer->getConnection()->fetchAll($stmt);
$table = $installer->getTable('catalog/eav_attribute');
foreach ($result as $data) {
    $installer->getConnection()->insert($table, $data);
}

$describe = $installer->getConnection()->describeTable($installer->getTable('catalog/eav_attribute'));
foreach ($describe as $columnData) {
    if ($columnData['COLUMN_NAME'] == 'attribute_id') {
        continue;
    }
    $installer->getConnection()->dropColumn($installer->getTable('eav/attribute'), $columnData['COLUMN_NAME']);
}

$prefix = Mage_Catalog_Model_Entity_Attribute::MODULE_NAME . Mage_Core_Model_Translate::SCOPE_SEPARATOR;
$sql = "
    INSERT
        INTO `{$installer->getTable('eav/attribute_label')}` (`attribute_id`, `store_id`, `value`)
        SELECT
            `attribute`.attribute_id, `translate`.store_id, `translate`.translate
        FROM
            `{$installer->getTable('eav/attribute')}` AS `attribute`
            INNER JOIN `{$installer->getTable('core/translate')}` AS `translate` ON `translate`.string = CONCAT('{$prefix}', `attribute`.frontend_label)
        WHERE
            `translate`.store_id != 0
";
$installer->getConnection()->query($sql);

$installer->endSetup();
