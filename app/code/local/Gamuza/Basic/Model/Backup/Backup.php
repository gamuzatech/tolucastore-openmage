<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Backup file item model
 */
class Gamuza_Basic_Model_Backup_Backup extends Mage_Backup_Model_Backup
{
    /* internal constants */
    public const COMPRESS_RATE = 6;

    public const XML_PATH_GLOBAL_RESOURCES_DEFAULT_SETUP_CONNECTION = 'global/resources/default_setup/connection';

    public function _write ($text, $filename)
    {
        $tempname = tempnam (sys_get_temp_dir (), 'backup_');

        file_put_contents ($tempname, $text);

        $command = sprintf(
            ' set -euo pipefail && ' .
            " cat %s | pigz -c >> %s ",
            escapeshellarg ($tempname),
            escapeshellarg ($filename)
        );

        $command = sprintf ('bash -c " %s "', $command);

        $result = exec ($command, $output, $code);

        if ($code != 0)
        {
            Mage::exception ('Mage_Backup', Mage::helper ('backup')->__('An error occurred while writing to the backup file "%s".', $filename));
        }

        unlink ($tempname);

        return $this;
    }

    public function _dump ($tableName, $filename)
    {
        $xml = Mage::getConfig ()->getNode (self::XML_PATH_GLOBAL_RESOURCES_DEFAULT_SETUP_CONNECTION);

        $hostname = strval ($xml->host);
        $username = strval ($xml->username);
        $password = strval ($xml->password);
        $database = strval ($xml->dbname);

        $command = sprintf(
            ' set -euo pipefail && ' .
            ' mariadb-dump ' .
            ' --skip-comments ' .
            ' --single-transaction --quick --skip-extended-insert --routines --triggers --hex-blob ' .
            " --socket=%s --user=%s --password=%s %s %s " .
            " | sed -E '/^\/\*!4[0-9]{4} SET /d' " .
            " | pigz -c >> %s ",
            escapeshellarg ($hostname),
            escapeshellarg ($username),
            escapeshellarg ($password),
            escapeshellarg ($database),
            escapeshellarg ($tableName),
            escapeshellarg ($filename)
        );

        $command = sprintf ('bash -c " %s "', $command);

        $result = exec ($command, $output, $code);

        if ($code != 0)
        {
            Mage::throwException (sprintf ('mariadb-dump ERROR %d', $code));
        }
    }
}

