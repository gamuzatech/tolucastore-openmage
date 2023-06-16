<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog category
 */
class Gamuza_Basic_Model_Catalog_Category extends Mage_Catalog_Model_Category
{
    /**
     * Retrieve Layout Update Handle name
     *
     * @return string
     */
    public function getLayoutUpdateHandle ()
    {
        if ($this->getLayoutCustomHandle ())
        {
            return $this->getLayoutCustomHandle ();
        }

        return parent::getLayoutUpdateHandle ();
    }
}

