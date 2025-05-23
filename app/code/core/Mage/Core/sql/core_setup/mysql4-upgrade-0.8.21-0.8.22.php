<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Core
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->run("
CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache')}` (
        `id` VARCHAR(255) NOT NULL,
        `data` mediumblob,
        `create_time` int(11),
        `update_time` int(11),
        `expire_time` int(11),
        PRIMARY KEY  (`id`),
        KEY `IDX_EXPIRE_TIME` (`expire_time`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache_tag')}` (
    `tag` VARCHAR(255) NOT NULL,
    `cache_id` VARCHAR(255) NOT NULL,
    KEY `IDX_TAG` (`tag`),
    KEY `IDX_CACHE_ID` (`cache_id`),
    CONSTRAINT `FK_CORE_CACHE_TAG` FOREIGN KEY (`cache_id`) REFERENCES `{$installer->getTable('core/cache')}` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache_option')}` (
        `code` VARCHAR(32) NOT NULL,
        `value` tinyint(3),
        PRIMARY KEY  (`code`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
