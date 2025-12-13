<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Balance API
 */
class Toluca_PDV_Model_Balance_Api extends Mage_Api_Model_Resource_Abstract
{
    public function weight ()
    {
        $pairs = Mage::helper ('pdv')->getStoreConfig ('balance_device.%');

        if (!array_key_exists ('balance_device.config', $pairs))
        {
            return -1;
        }

        $result = -1;

        $config = $pairs ['balance_device.config'];

        switch ($config)
        {
            case 'serial':
            {
                $device = $pairs ['balance_device.port_name'];

                $fp = fopen ($device, 'r');

                if (!$fp)
                {
                    $error_message = Mage::helper ('pdv')->__('Cannot open filename');
                    $error_code = -1;

                    $this->_fault ('data_invalid', sprintf ('%s [ %s ]', $error_message, $error_code));
                }

                stream_set_blocking ($fp, false);

                while (($buffer = fgets ($fp)) !== false)
                {
                    $result = $buffer;
                }

                fclose ($fp);

                break;
            }
            case 'tcp':
            {
                $ip       = $pairs ['balance_device.tcp_ip'];
                $port     = $pairs ['balance_device.tcp_port'];
                $attempts = $pairs ['balance_device.attempts'];
                $timeout  = $pairs ['balance_device.timeout'];

                /*
                $fp = fsockopen ($ip, $port, $error_code, $error_message, floatval ($attempts));
                */

                $fp = stream_socket_client ("tcp://{$ip}:{$port}", $error_code, $error_message, floatval ($attempts));

                if (!$fp)
                {
                    $error_message = Mage::helper ('pdv')->__($error_message);

                    $this->_fault ('data_invalid', sprintf ('%s [ %s ]', $error_message, $error_code));
                }

                stream_set_timeout ($fp, intval ($timeout));

                /*
                while (($buffer = fgets ($fp)) !== false)
                {
                    $result = $buffer;
                }
                */

                $buffer = "";

                while (strpos ($buffer, "\r") === false)
                {
                    $chunk = fread ($fp, 16);

                    if (empty ($chunk)) continue;

                    $buffer .= $chunk;
                }

                fclose ($fp);

                $result = trim (str_replace (["\x02", "\r", "\n"], "", $buffer));
                $result = floatval ($result) * 1000; // gram

                break;
            }
        }

        return $result;
    }
}
