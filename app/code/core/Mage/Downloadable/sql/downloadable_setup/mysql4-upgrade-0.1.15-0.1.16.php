<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Downloadable
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->addAttribute('catalog_product', 'links_exist', [
    'type'                      => 'int',
    'backend'                   => '',
    'frontend'                  => '',
    'label'                     => '',
    'input'                     => '',
    'class'                     => '',
    'source'                    => '',
    'global'                    => true,
    'visible'                   => false,
    'required'                  => false,
    'user_defined'              => false,
    'default'                   => '0',
    'searchable'                => false,
    'filterable'                => false,
    'comparable'                => false,
    'visible_on_front'          => false,
    'unique'                    => false,
    'apply_to'                  => 'downloadable',
    'is_configurable'           => false,
    'used_in_product_listing'   => 1,
]);

$newAttributeId = $installer->getAttributeId('catalog_product', 'links_exist');
$entityTypeId = $installer->getEntityTypeId('catalog_product');
$newAttributeTable = $installer->getAttributeTable($entityTypeId, 'links_exist');

$defaultValue = 1;
$installer->run("
INSERT INTO `{$newAttributeTable}`
    (entity_id, entity_type_id, attribute_id, value)
     SELECT distinct product_id,
        '{$entityTypeId}' AS entity_type_id,
        '{$newAttributeId}' AS attribute_id,
        '{$defaultValue}' AS value
    FROM `{$installer->getTable('downloadable/link')}`
");

$installer->endSetup();
