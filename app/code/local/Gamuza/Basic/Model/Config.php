<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Config
{
    public function getAttributeSets ($entityType)
    {
        $entityTypeId = Mage::getSingleton ('eav/config')
            ->getEntityType ($entityType)
            ->getEntityTypeId ()
        ;

        $collection = Mage::getResourceModel ('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter ($entityTypeId)
            ->setOrder ('attribute_set_id')
        ;

        return $collection;
    }
}

