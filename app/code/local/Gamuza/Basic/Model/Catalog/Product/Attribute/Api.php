<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product attribute api
 */
class Gamuza_Basic_Model_Catalog_Product_Attribute_Api
    extends Mage_Catalog_Model_Product_Attribute_Api
{
    /**
     * Retrieve attributes from specified attribute set
     *
     * @param int $setId
     * @return array
     */
    public function items($setId)
    {
        $attributes = Mage::getModel('catalog/product')->getResource()
                ->loadAllAttributes()
                ->getSortedAttributes($setId);
        $result = [];

        foreach ($attributes as $attribute)
        {
            /** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
            if ((!$attribute->getId() || $attribute->isInSet($setId))
                    && $this->_isAllowedAttribute($attribute)
            ) {
                if (!$attribute->getId() || $attribute->isScopeGlobal())
                {
                    $scope = 'global';
                }
                elseif ($attribute->isScopeWebsite())
                {
                    $scope = 'website';
                }
                else
                {
                    $scope = 'store';
                }

                $result[] = array(
                    'attribute_id' => $attribute->getId(),
                    'code'         => $attribute->getAttributeCode(),
                    'type'         => $attribute->getFrontendInput(),
                    'label'        => $attribute->getFrontendLabel(),
                    'required'     => $attribute->getIsRequired(),
                    'scope'        => $scope,
                );
            }
        }

        return $result;
    }
}

