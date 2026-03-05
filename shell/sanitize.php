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
    $coreConfig = Mage::getModel ('core/config');

    $coreConfig->deleteConfig ('accounting.name',  'desktop', -999999);
    $coreConfig->deleteConfig ('accounting.email', 'desktop', -999999);

    $coreConfig->deleteConfig ('email.user',     'desktop', -999999);
    $coreConfig->deleteConfig ('email.password', 'desktop', -999999);

    $coreConfig->deleteConfig ('mega_cmd.email',        'desktop', -999999);
    $coreConfig->deleteConfig ('mega_cmd.password',     'desktop', -999999);
    $coreConfig->deleteConfig ('mega_cmd.2fa',          'desktop', -999999);
    $coreConfig->deleteConfig ('mega_cmd.recovery_key', 'desktop', -999999);
    $coreConfig->deleteConfig ('mega_cmd.session_id',   'desktop', -999999);

    $coreConfig->deleteConfig ('system.accounting_on_login', 'desktop', -999999);

    $coreConfig->saveConfig ('system.cron_username', getenv ('USER'), 'desktop', -999999);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

