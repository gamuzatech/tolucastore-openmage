<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Log_Writer extends Zend_Log_Writer_Stream
{
    public function __construct ($streamOrUrl, $mode = null)
    {
        parent::__construct ($streamOrUrl, $mode);

        if (is_string ($streamOrUrl) && file_exists ($streamOrUrl))
        {
            @ chmod ($streamOrUrl, 0666);

            $directory = dirname ($streamOrUrl);

            if (is_dir ($directory))
            {
                @ chmod ($directory, 0777);
            }
        }
    }
}

