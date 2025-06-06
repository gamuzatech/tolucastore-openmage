<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Eav
 */

/**
 * Eav Resource Config model
 *
 * @package    Mage_Eav
 */
class Mage_Eav_Model_Resource_Config extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Array of entity types
     *
     * @var array
     */
    protected static $_entityTypes   = [];

    /**
     * Array of attributes
     *
     * @var array
     */
    protected static $_attributes    = [];

    protected function _construct()
    {
        $this->_init('eav/entity_type', 'entity_type_id');
    }

    /**
     * Load all entity types
     *
     * @return $this
     */
    protected function _loadTypes()
    {
        $adapter = $this->_getReadAdapter();
        if (!$adapter) {
            return $this;
        }
        if (empty(self::$_entityTypes)) {
            $select = $adapter->select()->from($this->getMainTable());
            $data   = $adapter->fetchAll($select);
            foreach ($data as $row) {
                self::$_entityTypes['by_id'][$row['entity_type_id']] = $row;
                self::$_entityTypes['by_code'][$row['entity_type_code']] = $row;
            }
        }

        return $this;
    }

    /**
     * Load attribute types
     *
     * @param int $typeId
     * @return array
     */
    protected function _loadTypeAttributes($typeId)
    {
        if (!isset(self::$_attributes[$typeId])) {
            $adapter = $this->_getReadAdapter();
            $bind    = ['entity_type_id' => $typeId];
            $select  = $adapter->select()
                ->from($this->getTable('eav/attribute'))
                ->where('entity_type_id = :entity_type_id');

            self::$_attributes[$typeId] = $adapter->fetchAll($select, $bind);
        }

        return self::$_attributes[$typeId];
    }

    /**
     * Retrieve entity type data
     *
     * @param string $entityType
     * @return array
     */
    public function fetchEntityTypeData($entityType)
    {
        $this->_loadTypes();

        if (is_numeric($entityType)) {
            $info = self::$_entityTypes['by_id'][$entityType] ?? null;
        } else {
            $info = self::$_entityTypes['by_code'][$entityType] ?? null;
        }

        $data = [];
        if ($info) {
            $data['entity']     = $info;
            $attributes         = $this->_loadTypeAttributes($info['entity_type_id']);
            $data['attributes'] = [];
            foreach ($attributes as $attribute) {
                $data['attributes'][$attribute['attribute_id']] = $attribute;
                $data['attributes'][$attribute['attribute_code']] = $attribute['attribute_id'];
            }
        }

        return $data;
    }
}
