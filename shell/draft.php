<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));

require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';

if (!Mage::isInstalled())
{
    echo 'Application is not installed yet, please complete install wizard first.';

    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

try
{
    Mage::app('admin', 'store', $mageRunOptions)->setUseSessionInUrl(false);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit;
}

umask(0);

try
{
    if ($argc != 2) exit(1);

    Mage::app()->getTranslator()->init(Mage_Core_Model_App_Area::AREA_ADMINHTML, true);

    $order = strrpos($argv[1], '-');

    $orderIncrementId = substr($argv[1], 0, $order);
    $orderProtectCode = substr($argv[1], $order + 1, 6);

    $contents = Mage::getModel('mobile/order_api')->draft($orderIncrementId, $orderProtectCode);

    $dir = Mage::getConfig()->getOptions()->getVarDir() . DS . 'draft';

    mkdir($dir, 0777, true);

    $file = sprintf('%s%s%s-%s-%s.txt', $dir, DS, 'order', $orderIncrementId, $orderProtectCode);

    file_put_contents($file, $contents);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

