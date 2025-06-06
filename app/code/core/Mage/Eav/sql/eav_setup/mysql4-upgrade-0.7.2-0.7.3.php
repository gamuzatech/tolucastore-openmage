<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Eav
 */

/** @var Mage_Eav_Model_Entity_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('eav_entity_type'), 'entity_model', 'VARCHAR(255) NOT NULL after entity_type_code');
$installer->getConnection()->addColumn($installer->getTable('eav_entity_type'), 'attribute_model', 'VARCHAR(255) NOT NULL after entity_model');

$installer->endSetup();
