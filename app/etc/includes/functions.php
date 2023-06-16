<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * https://www.php.net/manual/en/function.strstr.php#111455
 */
if (!function_exists ('strrstr'))
{
    function strrstr ($haystack, $needle, $before_needle = false)
    {
        $rpos = strrpos ($haystack, $needle);

        if ($rpos === false)
        {
            return false;
        }

        if ($before_needle == false)
        {
            return substr ($haystack, $rpos);
        }
        else
        {
            return substr ($haystack, 0, $rpos);
        }
    }
}

