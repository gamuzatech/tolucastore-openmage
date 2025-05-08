<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage
 */

/**
 * Apply workaround for the libxml PHP bugs:
 * @link https://bugs.php.net/bug.php?id=62577
 * @link https://bugs.php.net/bug.php?id=64938
 */
if ((LIBXML_VERSION < 20900) && function_exists('libxml_disable_entity_loader')) {
    libxml_disable_entity_loader(false);
}

ini_set('session.sid_bits_per_character', 5);
ini_set('session.sid_length', 248);

function shell_get_args($result = array())
{
    global $argv;

    array_shift($argv);

    foreach($argv as $arg)
    {
        $result = array_merge($result, explode(',', $arg));
    }

    return $result;
}

