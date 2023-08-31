<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Attribute_Set
{
    public function toOptionArray ()
    {
        $result = array ();

        $entityTypeId = Mage::getSingleton ('eav/config')
            ->getEntityType (Mage_Catalog_Model_Product::ENTITY)
            ->getEntityTypeId ()
        ;

        $collection = Mage::getResourceModel ('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter ($entityTypeId)
            ->setOrder ('attribute_set_id')
        ;

        foreach ($collection as $attributeSet)
        {
            $attributeSetId   = $attributeSet->getAttributeSetId ();
            $attributeSetName = $attributeSet->getAttributeSetName ();

            $result [] = array (
                'value' => $attributeSetId,
                'label' => sprintf ('%s - %s', $attributeSetName, $attributeSetId),
            );
        }

        return $result;
    }
}

