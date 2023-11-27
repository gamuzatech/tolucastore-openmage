<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));

require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';

if (!Mage::isInstalled())
{
    echo "Application is not installed yet, please complete install wizard first.";

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
    $args = shell_get_args ();

    $type = count ($args) > 0 ? $args [0] : null;

    Mage::getModel ('basic/magento_api')->backup ($type);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

