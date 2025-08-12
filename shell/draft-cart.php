<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));

require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';

if (!Mage::isInstalled())
{
    echo 'Application is not installed yet, please complete install wizard first.';

    exit(255);
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

try
{
    Mage::app('admin')->setUseSessionInUrl(false);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

umask(0);

try
{
    if ($argc != 2) exit(1);

    Mage::app()->getTranslator()->init(Mage_Core_Model_App_Area::AREA_ADMINHTML, true);

    $quote = strrpos($argv[1], '-');

    $id     = substr($argv[1], 0, $quote);
    $prefix = substr($argv[1], $quote + 1);

    $code = hash('crc32b', $id);

    Mage::getSingleton('core/session', array('name' => Mage_Core_Model_App_Area::AREA_ADMINHTML));

    Mage::app ()->getStore ()->setConfig (
        Gamuza_Mobile_Helper_Data::XML_PATH_DEFAULT_EMAIL_PREFIX, $prefix
    );

    $contents = Mage::getModel('mobile/cart_api')->draft($code);

    $dir = Mage::getConfig()->getOptions()->getVarDir() . DS . 'draft';

    mkdir($dir, 0777, true);

    $file = sprintf('%s%s%s-%s-%s.txt', $dir, DS, 'quote', $id, $code);

    file_put_contents($file, $contents);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

