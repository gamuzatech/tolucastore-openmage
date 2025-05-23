<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Catalog
 */

/**
 * Catalog product EAV additional attribute resource collection
 *
 * @package    Mage_Catalog
 */
class Mage_Catalog_Model_Resource_Product_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * Resource model initialization
     *
     */
    protected function _construct()
    {
        $this->_init('catalog/resource_eav_attribute', 'eav/entity_attribute');
    }

    /**
     * initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $entityTypeId = (int) Mage::getModel('eav/entity')->setType(Mage_Catalog_Model_Product::ENTITY)->getTypeId();
        $columns = $this->getConnection()->describeTable($this->getResource()->getMainTable());
        unset($columns['attribute_id']);
        $retColumns = [];
        /** @var Mage_Core_Model_Resource_Helper_Mysql4 $helper */
        $helper = Mage::getResourceHelper('core');
        foreach ($columns as $labelColumn => $columnData) {
            $retColumns[$labelColumn] = $labelColumn;
            if ($columnData['DATA_TYPE'] == Varien_Db_Ddl_Table::TYPE_TEXT) {
                $retColumns[$labelColumn] = $helper->castField('main_table.' . $labelColumn);
            }
        }
        $this->getSelect()
            ->from(['main_table' => $this->getResource()->getMainTable()], $retColumns)
            ->join(
                ['additional_table' => $this->getTable('catalog/eav_attribute')],
                'additional_table.attribute_id = main_table.attribute_id',
            )
            ->where('main_table.entity_type_id = ?', $entityTypeId);
        return $this;
    }

    /**
     * Specify attribute entity type filter.
     * Entity type is defined.
     *
     * @param  int $typeId
     * @return $this
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Return array of fields to load attribute values
     *
     * @return array
     */
    protected function _getLoadDataFields()
    {
        return array_merge(
            parent::_getLoadDataFields(),
            [
                'additional_table.is_global',
                'additional_table.is_html_allowed_on_front',
                'additional_table.is_wysiwyg_enabled',
            ],
        );
    }

    /**
     * Remove price from attribute list
     *
     * @return $this
     */
    public function removePriceFilter()
    {
        return $this->addFieldToFilter('main_table.attribute_code', ['neq' => 'price']);
    }

    /**
     * Specify "is_visible_in_advanced_search" filter
     *
     * @return $this
     */
    public function addDisplayInAdvancedSearchFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible_in_advanced_search', 1);
    }

    /**
     * Specify "is_filterable" filter
     *
     * @return $this
     */
    public function addIsFilterableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_filterable', ['gt' => 0]);
    }

    /**
     * Add filterable in search filter
     *
     * @return $this
     */
    public function addIsFilterableInSearchFilter()
    {
        return $this->addFieldToFilter('additional_table.is_filterable_in_search', ['gt' => 0]);
    }

    /**
     * Specify filter by "is_visible" field
     *
     * @return $this
     */
    public function addVisibleFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }

    /**
     * Specify "is_searchable" filter
     *
     * @return $this
     */
    public function addIsSearchableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_searchable', 1);
    }

    /**
     * Specify filter for attributes that have to be indexed
     *
     * @param bool $addRequiredCodes
     * @return $this
     */
    public function addToIndexFilter($addRequiredCodes = false)
    {
        $conditions = [
            'additional_table.is_searchable = 1',
            'additional_table.is_visible_in_advanced_search = 1',
            'additional_table.is_filterable > 0',
            'additional_table.is_filterable_in_search = 1',
            'additional_table.used_for_sort_by = 1',
        ];

        if ($addRequiredCodes) {
            $conditions[] = $this->getConnection()->quoteInto(
                'main_table.attribute_code IN (?)',
                ['status', 'visibility'],
            );
        }

        $this->getSelect()->where(sprintf('(%s)', implode(' OR ', $conditions)));

        return $this;
    }

    /**
     * Specify filter for attributes used in quick search
     *
     * @return $this
     */
    public function addSearchableAttributeFilter()
    {
        $this->getSelect()->where(
            'additional_table.is_searchable = 1 OR ' .
            $this->getConnection()->quoteInto('main_table.attribute_code IN (?)', ['status', 'visibility']),
        );

        return $this;
    }
}
