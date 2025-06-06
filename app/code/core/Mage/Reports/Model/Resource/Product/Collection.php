<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Reports
 */

/**
 * Products Report collection
 *
 * @package    Mage_Reports
 */
class Mage_Reports_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    public const SELECT_COUNT_SQL_TYPE_CART           = 1;

    /**
     * Product entity identifier
     *
     * @var int
     */
    protected $_productEntityId;

    /**
     * Product entity table name
     *
     * @var string
     */
    protected $_productEntityTableName;

    /**
     * Product entity type identifier
     *
     * @var int
     */
    protected $_productEntityTypeId;

    /**
     * select count
     *
     * @var int
     */
    protected $_selectCountSqlType               = 0;

    /**
     * Init main class options
     *
     */
    public function __construct()
    {
        $product = Mage::getResourceSingleton('catalog/product');
        $this->setProductEntityId($product->getEntityIdField());
        $this->setProductEntityTableName($product->getEntityTable());
        $this->setProductEntityTypeId($product->getTypeId());

        parent::__construct();
    }
    /**
     * Set Type for COUNT SQL Select
     *
     * @param int $type
     * @return $this
     */
    public function setSelectCountSqlType($type)
    {
        $this->_selectCountSqlType = $type;
        return $this;
    }

    /**
     * Set product entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setProductEntityId($entityId)
    {
        $this->_productEntityId = (int) $entityId;
        return $this;
    }

    /**
     * Get product entity id
     *
     * @return int
     */
    public function getProductEntityId()
    {
        return $this->_productEntityId;
    }

    /**
     * Set product entity table name
     *
     * @param string $value
     * @return $this
     */
    public function setProductEntityTableName($value)
    {
        $this->_productEntityTableName = $value;
        return $this;
    }

    /**
     * Get product entity table name
     *
     * @return string
     */
    public function getProductEntityTableName()
    {
        return $this->_productEntityTableName;
    }

    /**
     * Set product entity type id
     *
     * @param int $value
     * @return $this
     */
    public function setProductEntityTypeId($value)
    {
        $this->_productEntityTypeId = $value;
        return $this;
    }

    /**
     * Get product entity tyoe id
     *
     * @return int
     */
    public function getProductEntityTypeId()
    {
        return  $this->_productEntityTypeId;
    }

    /**
     * Join fields
     *
     * @return $this
     */
    protected function _joinFields()
    {
        $this->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('price');

        return $this;
    }

    /**
     * Get select count sql
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        if ($this->_selectCountSqlType == self::SELECT_COUNT_SQL_TYPE_CART) {
            $countSelect = clone $this->getSelect();
            $countSelect->reset()
                ->from(
                    ['quote_item_table' => $this->getTable('sales/quote_item')],
                    ['COUNT(DISTINCT quote_item_table.product_id)'],
                )
                ->join(
                    ['quote_table' => $this->getTable('sales/quote')],
                    'quote_table.entity_id = quote_item_table.quote_id AND quote_table.is_active = 1',
                    [],
                );
            return $countSelect;
        }

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->reset(Zend_Db_Select::GROUP);
        $countSelect->reset(Zend_Db_Select::HAVING);
        $countSelect->columns('count(DISTINCT e.entity_id)');

        return $countSelect;
    }

    /**
     * Add carts count
     *
     * @return $this
     */
    public function addCartsCount()
    {
        $countSelect = clone $this->getSelect();
        $countSelect->reset();

        $countSelect->from(['quote_items' => $this->getTable('sales/quote_item')], 'COUNT(*)')
            ->join(
                ['quotes' => $this->getTable('sales/quote')],
                'quotes.entity_id = quote_items.quote_id AND quotes.is_active = 1',
                [],
            )
            ->where('quote_items.product_id = e.entity_id');

        $this->getSelect()
            ->columns(['carts' => "({$countSelect})"])
            ->group("e.{$this->getProductEntityId()}")
            ->having('carts > ?', 0);

        return $this;
    }

    /**
     * Add orders count
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function addOrdersCount($from = '', $to = '')
    {
        $orderItemTableName = $this->getTable('sales/order_item');
        $productFieldName   = sprintf('e.%s', $this->getProductEntityId());

        $this->getSelect()
            ->joinLeft(
                ['order_items' => $orderItemTableName],
                "order_items.product_id = {$productFieldName}",
                [],
            )
            ->columns(['orders' => 'COUNT(order_items2.item_id)'])
            ->group($productFieldName);

        $dateFilter = ['order_items2.item_id = order_items.item_id'];
        if ($from != '' && $to != '') {
            $dateFilter[] = $this->_prepareBetweenSql('order_items2.created_at', $from, $to);
        }

        $this->getSelect()
            ->joinLeft(
                ['order_items2' => $orderItemTableName],
                implode(' AND ', $dateFilter),
                [],
            );

        return $this;
    }

    /**
     * Add ordered qty's
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function addOrderedQty($from = '', $to = '')
    {
        $adapter              = $this->getConnection();
        $compositeTypeIds     = Mage::getSingleton('catalog/product_type')->getCompositeTypes();
        $orderTableAliasName  = $adapter->quoteIdentifier('order');

        $orderJoinCondition   = [
            $orderTableAliasName . '.entity_id = order_items.order_id',
            $adapter->quoteInto("{$orderTableAliasName}.state <> ?", Mage_Sales_Model_Order::STATE_CANCELED),

        ];

        $productJoinCondition = [
            $adapter->quoteInto('(e.type_id NOT IN (?))', $compositeTypeIds),
            'e.entity_id = order_items.product_id',
            $adapter->quoteInto('e.entity_type_id = ?', $this->getProductEntityTypeId()),
        ];

        if ($from != '' && $to != '') {
            $fieldName            = $orderTableAliasName . '.created_at';
            $orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()
            ->from(
                ['order_items' => $this->getTable('sales/order_item')],
                [
                    'ordered_qty' => 'SUM(order_items.qty_ordered)',
                    'order_items_name' => 'order_items.name',
                ],
            )
            ->joinInner(
                ['order' => $this->getTable('sales/order')],
                implode(' AND ', $orderJoinCondition),
                [],
            )
            ->joinLeft(
                ['e' => $this->getProductEntityTableName()],
                implode(' AND ', $productJoinCondition),
                [
                    'entity_id' => 'order_items.product_id',
                    'entity_type_id' => 'e.entity_type_id',
                    'attribute_set_id' => 'e.attribute_set_id',
                    'type_id' => 'e.type_id',
                    'sku' => 'e.sku',
                    'has_options' => 'e.has_options',
                    'required_options' => 'e.required_options',
                    'created_at' => 'e.created_at',
                    'updated_at' => 'e.updated_at',
                ],
            )
            ->where('parent_item_id IS NULL')
            ->group('order_items.product_id')
            ->having('SUM(order_items.qty_ordered) > ?', 0);
        return $this;
    }

    /**
     * Set order
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function setOrder($attribute, $dir = self::SORT_ORDER_DESC)
    {
        if (in_array($attribute, ['carts', 'orders', 'ordered_qty'])) {
            $this->getSelect()->order($attribute . ' ' . $dir);
        } else {
            parent::setOrder($attribute, $dir);
        }

        return $this;
    }

    /**
     * Add views count
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function addViewsCount($from = '', $to = '')
    {
        /**
         * Getting event type id for catalog_product_view event
         */
        foreach (Mage::getModel('reports/event_type')->getCollection() as $eventType) {
            if ($eventType->getEventName() == 'catalog_product_view') {
                $productViewEvent = (int) $eventType->getId();
                break;
            }
        }

        if (!isset($productViewEvent)) {
            return $this;
        }

        $this->getSelect()->reset()
            ->from(
                ['report_table_views' => $this->getTable('reports/event')],
                ['views' => 'COUNT(report_table_views.event_id)'],
            )
            ->join(
                ['e' => $this->getProductEntityTableName()],
                $this->getConnection()->quoteInto(
                    'e.entity_id = report_table_views.object_id AND e.entity_type_id = ?',
                    $this->getProductEntityTypeId(),
                ),
            )
            ->where('report_table_views.event_type_id = ?', $productViewEvent)
            ->group('e.entity_id')
            ->order('views ' . self::SORT_ORDER_DESC)
            ->having('COUNT(report_table_views.event_id) > ?', 0);

        if ($from != '' && $to != '') {
            $this->getSelect()
                ->where('logged_at >= ?', $from)
                ->where('logged_at <= ?', $to);
        }

        $this->_useAnalyticFunction = true;
        return $this;
    }

    /**
     * Prepare between sql
     *
     * @param  string $fieldName Field name with table suffix ('created_at' or 'main_table.created_at')
     * @param  string $from
     * @param  string $to
     * @return string Formatted sql string
     */
    protected function _prepareBetweenSql($fieldName, $from, $to)
    {
        return sprintf(
            '(%s BETWEEN %s AND %s)',
            $fieldName,
            $this->getConnection()->quote($from),
            $this->getConnection()->quote($to),
        );
    }

    /**
     * Add store restrictions to product collection
     *
     * @param  array $storeIds
     * @param  array $websiteIds
     * @return $this
     */
    public function addStoreRestrictions($storeIds, $websiteIds)
    {
        if (!is_array($storeIds)) {
            $storeIds = [$storeIds];
        }
        if (!is_array($websiteIds)) {
            $websiteIds = [$websiteIds];
        }

        $filters = $this->_productLimitationFilters;
        if (isset($filters['store_id'])) {
            if (!in_array($filters['store_id'], $storeIds)) {
                $this->addStoreFilter($filters['store_id']);
            } else {
                $this->addStoreFilter($this->getStoreId());
            }
        } else {
            $this->addWebsiteFilter($websiteIds);
        }

        return $this;
    }
}
