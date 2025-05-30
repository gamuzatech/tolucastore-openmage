<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Weee
 */

/** @var Mage_Weee_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->addAttribute('quote_item', 'base_weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('quote_item', 'base_weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('order_item', 'base_weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('order_item', 'base_weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('creditmemo_item', 'base_weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('creditmemo_item', 'base_weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('creditmemo_item', 'weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('creditmemo_item', 'weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('invoice_item', 'base_weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('invoice_item', 'base_weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('invoice_item', 'weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('invoice_item', 'weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->endSetup();
