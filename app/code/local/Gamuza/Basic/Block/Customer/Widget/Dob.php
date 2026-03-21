<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies. (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Customer_Widget_Dob
    extends Mage_Customer_Block_Widget_Dob
{
    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml ($html)
    {
        if (Mage::getStoreConfigFlag (Gamuza_Basic_Helper_Data::XML_PATH_SALES_MINIMUM_AGE_ACTIVE))
        {
            $description = Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_SALES_MINIMUM_AGE_DESCRIPTION);

            return $html . '<p class="form-instructions">' . $description . '</p>';
        }

        return $html;
    }
}

