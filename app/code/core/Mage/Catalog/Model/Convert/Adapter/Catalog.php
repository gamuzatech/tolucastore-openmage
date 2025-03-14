<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Catalog
 */
class Mage_Catalog_Model_Convert_Adapter_Catalog extends Mage_Dataflow_Model_Convert_Adapter_Abstract
{
    /**
     * @return object
     */
    public function getResource()
    {
        if (!$this->_resource) {
            $this->_resource = Mage::getResourceSingleton('catalog_entity/convert');
        }
        return $this->_resource;
    }

    /**
     * @return $this
     */
    public function load()
    {
        $res = $this->getResource();

        $this->setData([
            'Products' => $res->exportProducts(),
            'Categories' => $res->exportCategories(),
            'Image Gallery' => $res->exportImageGallery(),
            'Product Links' => $res->exportProductLinks(),
            'Products in Categories' => $res->exportProductsInCategories(),
            'Products in Stores' => $res->exportProductsInStores(),
            'Attributes' => $res->exportAttributes(),
            'Attribute Sets' => $res->exportAttributeSets(),
            'Attribute Options' => $res->exportAttributeOptions(),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function save()
    {
        /*
        $res = $this->getResource();

        foreach (array('Attributes', 'Attribute Sets', 'Attribute Options', 'Products', 'Categories', ''))

        $this->setData

        echo "<pre>".print_r($this->getData(),1)."</pre>";

        */
        return $this;
    }
}
