<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product link model
 */
class Gamuza_Basic_Model_Catalog_Product_Image
    extends Mage_Catalog_Model_Product_Image
{
    /**
     * Set filenames for base file and new file
     *
     * @param string $file
     * @return $this
     */
    public function setBaseFile($file)
    {
        parent::setBaseFile($file);

        if (Mage::getStoreConfigFlag('catalog/product_image/use_original'))
        {
            $this->_newFile = $this->_baseFile;
        }

        return $this;
    }
}

