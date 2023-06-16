<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2019 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * customers defined options
 */
class Gamuza_Basic_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Option
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{
    public function getOptionValues()
    {
        $result = parent::getOptionValues();

        $options = array_reverse ($this->getProduct ()->getOptions (), true);

        foreach ($options as $_option)
        {
            foreach ($result as $id => $value)
            {
                if ($value->getId () == $_option->getOptionId ())
                {
                    $result [$id]['max_length'] = $_option->getMaxLength ();
                }
            }
        }

        return $result;
    }
}

