<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Review
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

//Fill table review/review_entity
$reviewEntityCodes = [
    Mage_Review_Model_Review::ENTITY_PRODUCT_CODE,
    Mage_Review_Model_Review::ENTITY_CUSTOMER_CODE,
    Mage_Review_Model_Review::ENTITY_CATEGORY_CODE,
];
foreach ($reviewEntityCodes as $entityCode) {
    $installer->getConnection()
            ->insert($installer->getTable('review/review_entity'), ['entity_code' => $entityCode]);
}

//Fill table review/review_entity
$reviewStatuses = [
    Mage_Review_Model_Review::STATUS_APPROVED       => 'Approved',
    Mage_Review_Model_Review::STATUS_PENDING        => 'Pending',
    Mage_Review_Model_Review::STATUS_NOT_APPROVED   => 'Not Approved',
];
foreach ($reviewStatuses as $k => $v) {
    $bind = [
        'status_id'     => $k,
        'status_code'   => $v,
    ];
    $installer->getConnection()->insertForce($installer->getTable('review/review_status'), $bind);
}
