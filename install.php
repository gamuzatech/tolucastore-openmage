<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage
 */

/**
 * There are two modes to run this script:
 *
 * 1. Dump available locale options (currencies, locales, timezones) and exit
 * php -f install.php -- --get_options
 *
 * The output can be eval'd in a regular PHP array of the following format:
 * array (
 *   'locale' =>
 *   array (
 *     0 =>
 *     array (
 *       'value' => 'zh_TW',
 *       'label' => 'Chinese (Taiwan)',
 *     ),
 *   ),
 *   'currency' =>
 *   array (
 *     0 =>
 *     array (
 *       'value' => 'zh_TW',
 *       'label' => 'Chinese (Taiwan)',
 *     ),
 *   ),
 *   'timezone' =>
 *   array (
 *     0 =>
 *     array (
 *       'value' => 'zh_TW',
 *       'label' => 'Chinese (Taiwan)',
 *     ),
 *   ),
 * );
 *
 * or parsed in any other way.
 *
 * 2. Perform the installation
 *
 *  php -f install.php -- --license_agreement_accepted yes \
 *  --locale en_US --timezone "America/Los_Angeles" --default_currency USD \
 *  --db_host localhost --db_name openmage_database --db_user openmage_user --db_pass 123123 \
 *  --db_prefix openmage_ \
 *  --url "http://openmage.example.com/" --use_rewrites yes \
 *  --use_secure yes --secure_base_url "https://openmage.example.com/" --use_secure_admin yes \
 *  --admin_lastname Owner --admin_firstname Store --admin_email "admin@example.com" \
 *  --admin_username admin --admin_password 123123 \
 *  --encryption_key "Encryption Key"
 *
 * Possible options are:
 * --license_agreement_accepted // required, it will accept 'yes' value only
 * Locale settings:
 * --locale                     // required, Locale
 * --timezone                   // required, Time Zone
 * --default_currency           // required, Default Currency
 * Database connection options:
 * --db_host                    // required, You can specify server port, ex.: localhost:3307
 *                              // If you are not using default UNIX socket, you can specify it
 *                              // here instead of host, ex.: /var/run/mysqld/mysqld.sock
 * --db_model                   // Database type (mysql4 by default)
 * --db_name                    // required, Database Name
 * --db_user                    // required, Database User Name
 * --db_pass                    // required, Database User Password
 * --db_prefix                  // optional, Database Tables Prefix
 *                              // No table prefix will be used if not specified
 * Session options:
 * --session_save <files|db>    // optional, where to store session data - in db or files. files by default
 * Web access options:
 * --admin_frontname <path>     // optional, admin panel path, "admin" by default
 * --url                        // required, URL the store is supposed to be available at
 * --skip_url_validation        // optional, skip validating base url during installation or not. No by default
 * --use_rewrites               // optional, Use Web Server (Apache) Rewrites,
 *                              // You could enable this option to use web server rewrites functionality for improved SEO
 *                              // Please make sure that mod_rewrite is enabled in Apache configuration
 * --use_secure                 // optional, Use Secure URLs (SSL)
 *                              // Enable this option only if you have SSL available.
 * --secure_base_url            // optional, Secure Base URL
 *                              // Provide a complete base URL for SSL connection.
 *                              // For example: https://www.mydomain.com/openmage/
 * --use_secure_admin           // optional, Run admin interface with SSL
 * Backend interface options:
 * --enable_charts              // optional, Enables Charts on the backend's dashboard
 * Admin user personal information:
 * --admin_lastname             // required, admin user last name
 * --admin_firstname            // required, admin user first name
 * --admin_email                // required, admin user email
 * Admin user login information:
 * --admin_username             // required, admin user login
 * --admin_password             // required, admin user password
 * Encryption key:
 * --encryption_key             // optional, will be automatically generated and displayed on success, if not specified
 *
 */

set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
require 'app/bootstrap.php';
require 'app/Mage.php';

$app = Mage::app('default');

/** @var Mage_Install_Model_Installer_Console $installer */
$installer = Mage::getSingleton('install/installer_console');

try {
    if (
        $installer->init($app)          // initialize installer
        && $installer->checkConsole()   // check if the script is run in shell, otherwise redirect to web-installer
        && $installer->setArgs()        // set and validate script arguments
        && $installer->install()        // do install
    ) {
        echo 'SUCCESS: ' . $installer->getEncryptionKey() . "\n";
        exit;
    }
} catch (Exception $e) {
    Mage::printException($e);
}

// print all errors if there were any
if ($installer instanceof Mage_Install_Model_Installer_Console) {
    if ($installer->getErrors()) {
        echo "\nFAILED\n";
        foreach ($installer->getErrors() as $error) {
            echo $error . "\n";
        }
    }
}
exit(1); // don't delete this as this should notify about failed installation
