<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_CatalogIndex
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->run("
    CREATE TABLE `{$installer->getTable('catalogindex_eav_tmp')}` (
        `store_id` smallint(5) unsigned NOT NULL default '0',
        `entity_id` int(10) unsigned NOT NULL default '0',
        `attribute_id` smallint(5) unsigned NOT NULL default '0',
        `value` int(11) NOT NULL default '0'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    insert into `{$installer->getTable('catalogindex_eav_tmp')}`
        select distinct store_id, entity_id, attribute_id, value
        from `{$installer->getTable('catalogindex_eav')}`;

    DROP TABLE `{$installer->getTable('catalogindex_eav')}`;

    CREATE TABLE `{$installer->getTable('catalogindex_eav')}` (
        `store_id` smallint(5) unsigned NOT NULL default '0',
        `entity_id` int(10) unsigned NOT NULL default '0',
        `attribute_id` smallint(5) unsigned NOT NULL default '0',
        `value` int(11) NOT NULL default '0',
        PRIMARY KEY  (`store_id`,`entity_id`,`attribute_id`,`value`),
        KEY `IDX_VALUE` (`value`),
        KEY `FK_CATALOGINDEX_EAV_ENTITY` (`entity_id`),
        KEY `FK_CATALOGINDEX_EAV_ATTRIBUTE` (`attribute_id`),
        KEY `FK_CATALOGINDEX_EAV_STORE` (`store_id`),
        CONSTRAINT `FK_CATALOGINDEX_EAV_ATTRIBUTE` FOREIGN KEY (`attribute_id`)
            REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_CATALOGINDEX_EAV_ENTITY` FOREIGN KEY (`entity_id`)
            REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_CATALOGINDEX_EAV_STORE` FOREIGN KEY (`store_id`)
            REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    insert into `{$installer->getTable('catalogindex_eav')}`
        select store_id, entity_id, attribute_id, value
        from `{$installer->getTable('catalogindex_eav_tmp')}`;

    DROP TABLE `{$installer->getTable('catalogindex_eav_tmp')}`;






    CREATE TABLE `{$installer->getTable('catalogindex_price_tmp')}` (
      `store_id` smallint(5) unsigned NOT NULL default '0',
      `entity_id` int(10) unsigned NOT NULL default '0',
      `attribute_id` smallint(5) unsigned NOT NULL default '0',
      `customer_group_id` smallint(3) unsigned NOT NULL default '0',
      `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
      `value` decimal(12,4) NOT NULL default '0.0000'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    insert into `{$installer->getTable('catalogindex_price_tmp')}`
        select distinct store_id, entity_id, attribute_id, customer_group_id, qty, value
        from `{$installer->getTable('catalogindex_price')}`;

    DROP TABLE `{$installer->getTable('catalogindex_price')}`;

    CREATE TABLE `{$installer->getTable('catalogindex_price')}` (
      `store_id` smallint(5) unsigned NOT NULL default '0',
      `entity_id` int(10) unsigned NOT NULL default '0',
      `attribute_id` smallint(5) unsigned NOT NULL default '0',
      `customer_group_id` smallint(3) unsigned NOT NULL default '0',
      `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
      `value` decimal(12,4) NOT NULL default '0.0000',
      KEY `IDX_VALUE` (`value`),
      KEY `IDX_QTY` (`qty`),
      KEY `FK_CATALOGINDEX_PRICE_ENTITY` (`entity_id`),
      KEY `FK_CATALOGINDEX_PRICE_ATTRIBUTE` (`attribute_id`),
      KEY `FK_CATALOGINDEX_PRICE_STORE` (`store_id`),
      KEY `FK_CATALOGINDEX_PRICE_CUSTOMER_GROUP` (`customer_group_id`),
      KEY `IDX_RANGE_VALUE` (`store_id`, `entity_id`,`attribute_id`,`customer_group_id`,`value`),
      CONSTRAINT `FK_CATALOGINDEX_PRICE_ATTRIBUTE` FOREIGN KEY (`attribute_id`)
        REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `FK_CATALOGINDEX_PRICE_ENTITY` FOREIGN KEY (`entity_id`)
        REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `FK_CATALOGINDEX_PRICE_STORE` FOREIGN KEY (`store_id`)
        REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    insert into `{$installer->getTable('catalogindex_price')}`
        select store_id, entity_id, attribute_id, customer_group_id, qty, value
        from `{$installer->getTable('catalogindex_price_tmp')}`;

    DROP TABLE `{$installer->getTable('catalogindex_price_tmp')}`;
");
