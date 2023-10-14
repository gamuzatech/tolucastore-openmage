<?php
/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2018-2023 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

chdir(__DIR__);

define('MAGENTO_ROOT', getcwd());

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
$maintenanceFile = 'maintenance.flag';
$maintenanceIpFile = 'maintenance.ip';

require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

#Varien_Profiler::enable();

umask(0);

/* Store or website code */
$mageRunCode = $_SERVER['MAGE_RUN_CODE'] ?? '';

/* Run store or run website */
$mageRunType = $_SERVER['MAGE_RUN_TYPE'] ?? 'store';

if (file_exists($maintenanceFile)) {
    $maintenanceBypass = false;

    if (file_exists($maintenanceIpFile)) {
        /* if maintenanceFile and maintenanceIpFile are set use Mage to get remote IP (in order to respect remote_addr_headers xml config) */
        Mage::init($mageRunCode, $mageRunType);
        $currentIp = Mage::helper('core/http')->getRemoteAddr();
        $allowedIps = explode(',', trim(file_get_contents($maintenanceIpFile)));

        if (in_array($currentIp, $allowedIps)) {
            /* IP address matches, bypass maintenanceMode */
            $maintenanceBypass = true;
        }
    }
    if (!$maintenanceBypass) {
        include_once __DIR__ . '/errors/503.php';
        exit;
    }

    // remove config cache to make the system check for DB updates
    $config = Mage::app()->getConfig();
    $config->getCache()->remove($config->getCacheId());
}

$httpXOriginalHost = @$_SERVER['HTTP_X_FORWARDED_HOST'];

if (!empty($httpXOriginalHost)) {
    Mage::init('admin');

    foreach (Mage::app()->getStores(false, false) as $store) {
        if (strpos($store->getBaseUrl(), $httpXOriginalHost) !== false) {
            $httpHost = $_SERVER['HTTP_HOST'];
            $_SERVER['HTTP_X_INBOUND_HOST'] = $httpHost;
            $_SERVER['HTTP_HOST'] = $httpXOriginalHost;
            $mageRunCode = $store->getCode();
            break;
        }
    }
}

Mage::run($mageRunCode, $mageRunType);
