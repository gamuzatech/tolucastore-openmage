<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_CatalogRule
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer  = $this;
$installer->startSetup();

$ruleGroupWebsiteTable = $installer->getTable('catalogrule/rule_group_website');

$installer->run("CREATE TABLE `{$ruleGroupWebsiteTable}` (
 `rule_id` int(10) unsigned NOT NULL default '0',
 `customer_group_id` smallint(5) unsigned default NULL,
 `website_id` smallint(5) unsigned default NULL,
 KEY `rule_id` (`rule_id`),
 KEY `customer_group_id` (`customer_group_id`),
 KEY `website_id` (`website_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin");

$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_RULE',
    $ruleGroupWebsiteTable,
    'rule_id',
    $installer->getTable('catalogrule/rule'),
    'rule_id',
    'CASCADE',
    'CASCADE',
);
$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_GROUP',
    $ruleGroupWebsiteTable,
    'customer_group_id',
    $installer->getTable('customer/customer_group'),
    'customer_group_id',
    'CASCADE',
    'CASCADE',
);
$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_WEBSITE',
    $ruleGroupWebsiteTable,
    'website_id',
    $installer->getTable('core/website'),
    'website_id',
    'CASCADE',
    'CASCADE',
);

$installer->run(
    "ALTER TABLE `{$ruleGroupWebsiteTable}` ADD PRIMARY KEY ( `rule_id` , `customer_group_id`, `website_id` )",
);

$installer->endSetup();
