<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Core
 */

/**
 * Modifier of queries, developed for backwards compatibility on MySQL,
 * while creating foreign keys
 *
 * @package    Mage_Core
 */
class Mage_Core_Model_Resource_Setup_Query_Modifier
{
    /**
     * MySQL adapter instance
     *
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_adapter;

    /**
     * Types of column we process for foreign keys
     *
     * @var array
     */
    protected $_processedTypes = ['tinyint', 'smallint', 'mediumint', 'int', 'longint'];

    /**
     * Inits query modifier
     *
     * @param Varien_Db_Adapter_Pdo_Mysql|array $args
     */
    public function __construct($args)
    {
        $this->_adapter = $args[0];
    }

    /**
     * Returns column definition from CREATE TABLE sql
     *
     * @param string $sql
     * @param string $column
     * @return array
     */
    protected function _getColumnDefinitionFromSql($sql, $column)
    {
        $result = null;
        foreach ($this->_processedTypes as $type) {
            $pattern = '/\s([^\s]+)\s+' . $type . '[^\s]*(\s+[^,]+)/i';
            if (!preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER)) {
                continue;
            }
            foreach ($matches as $match) {
                $gotColumn = $this->_prepareIdentifier($match[1]);
                if ($gotColumn != $column) {
                    continue;
                }

                $definition = $match[2];
                $unsigned = preg_match('/\sUNSIGNED/i', $definition) > 0;

                $result = [
                    'type' => $type,
                    'unsigned' => $unsigned,
                ];
                break;
            }
            if ($result) {
                break;
            }
        }

        return $result;
    }

    /**
     * Replaces first occurrence of $needle in a $haystack
     *
     * @param string $haystack
     * @param string $needle
     * @param string $replacement
     * @param bool $caseInsensitive
     * @return string
     */
    protected function _firstReplace($haystack, $needle, $replacement, $caseInsensitive = false)
    {
        $pos = $caseInsensitive ? stripos($haystack, $needle) : strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }

        return substr($haystack, 0, $pos) . $replacement . substr($haystack, $pos + strlen($needle));
    }

    /**
     * Fixes column definition in CREATE TABLE sql to match definition of column it's set to
     *
     * @param string $sql
     * @param string $column
     * @param array $refColumnDefinition
     * @return $this
     */
    protected function _fixColumnDefinitionInSql(&$sql, $column, $refColumnDefinition)
    {
        $pos = stripos($sql, "`{$column}`"); // First try to find column directly recorded
        if ($pos === false) {
            $pattern = '/[`\s]' . preg_quote($column, '/') . '[`\s]/i';
            if (!preg_match($pattern, $sql, $matches)) {
                return $this;
            }

            $columnEntry = $matches[0];
            $pos = strpos($sql, $columnEntry);
            if ($pos === false) {
                return $this;
            }
        }

        $startSql = substr($sql, 0, $pos);
        $restSql = substr($sql, $pos);

        // Column type definition
        $columnDefinition = $this->_getColumnDefinitionFromSql($sql, $column);
        if (!$columnDefinition) {
            return $this;
        }

        // Find pattern for type definition
        $pattern = '/\s*([^\s]+)\s+(' . $columnDefinition['type'] . '[^\s]*)\s+([^,]+)/i';
        if (!preg_match($pattern, $restSql, $matches)) {
            return $this;
        }

        // Replace defined type with needed type
        $typeDefined = $matches[2];
        $typeNeeded = $refColumnDefinition['type'];
        if ($refColumnDefinition['unsigned'] && !$columnDefinition['unsigned']) {
            $typeNeeded .= ' unsigned';
        }

        $restSql = $this->_firstReplace($restSql, $typeDefined, $typeNeeded);

        if (!$refColumnDefinition['unsigned'] && ($columnDefinition['unsigned'])) {
            $restSql = $this->_firstReplace($restSql, 'unsigned', '', true);
        }

        // Compose SQL back
        $sql = $startSql . $restSql;

        return $this;
    }

    /**
     * Fixes column definition in already existing table, so outgoing foreign key will be successfully set
     *
     * @param string $table
     * @param string $column
     * @param array $refColumnDefinition
     * @return $this
     */
    protected function _fixColumnDefinitionInTable($table, $column, $refColumnDefinition)
    {
        $description = $this->_adapter->fetchAll('DESCRIBE ' . $table);
        foreach ($description as $columnData) {
            $columnName = $this->_prepareIdentifier($columnData['Field']);
            if ($columnName != $column) {
                continue;
            }
            $definition = $refColumnDefinition['type'];
            if ($refColumnDefinition['unsigned']) {
                $definition .= ' UNSIGNED';
            }
            if ($columnData['Null'] == 'YES') {
                $definition .= ' NULL';
            } else {
                $definition .= ' NOT NULL';
            }
            if ($columnData['Default']) {
                $definition .= ' DEFAULT ' . $columnData['Default'];
            }
            if ($columnData['Extra']) {
                $definition .= ' ' . $columnData['Extra'];
            }

            $query = 'ALTER TABLE ' . $table . ' MODIFY COLUMN ' . $column . ' ' . $definition;
            $this->_adapter->query($query);
        }
        return $this;
    }

    /**
     * Returns column definition from already existing table
     *
     * @param string $table
     * @param string $column
     * @return array|null
     */
    protected function _getColumnDefinitionFromTable($table, $column)
    {
        $description = $this->_adapter->describeTable($table);
        if (!isset($description[$column])) {
            return null;
        }

        return [
            'type' => $this->_prepareIdentifier($description[$column]['DATA_TYPE']),
            'unsigned' => (bool) $description[$column]['UNSIGNED'],
        ];
    }

    /**
     * Returns whether table exists
     *
     * @param string $table
     * @return bool
     */
    protected function _tableExists($table)
    {
        $rows = $this->_adapter->fetchAll('SHOW TABLES');
        foreach ($rows as $row) {
            $tableFound = strtolower(current($row));
            if ($table == $tableFound) {
                return true;
            }
        }
        return false;
    }

    /**
     * Trims and lowercases identifier, to make common view of all of them
     *
     * @param string $identifier
     * @return string
     */
    protected function _prepareIdentifier($identifier)
    {
        return strtolower(trim($identifier, "`\n\r\t"));
    }

    /**
     * Processes query, modifies targeted columns to fit foreign keys restrictions
     *
     * @param string $sql
     * @param array $bind
     * @return $this
     */
    public function processQuery(&$sql, &$bind)
    {
        // Quick test to skip queries without foreign keys
        if (!stripos($sql, 'foreign')) {
            return $this;
        }

        // Find foreign keys set
        $pattern = '/CONSTRAINT\s+[^\s]+\s+FOREIGN\s+KEY[^(]+\\(([^),]+)\\)\s+REFERENCES\s+([^\s.]+)\s+\\(([^)]+)\\)/i';
        if (!preg_match_all($pattern, $sql, $matchesFk, PREG_SET_ORDER)) {
            return $this;
        }

        // Get current table name
        if (!preg_match('/\s*(CREATE|ALTER)\s+TABLE\s+([^\s.]+)/i', $sql, $match)) {
            return $this;
        }

        $operation = $this->_prepareIdentifier($match[1]);
        $table = $this->_prepareIdentifier($match[2]);

        // Process all
        foreach ($matchesFk as $match) {
            $column = $this->_prepareIdentifier($match[1]);
            $refTable = $this->_prepareIdentifier($match[2]);
            $refColumn = $this->_prepareIdentifier($match[3]);

            // Check tables existence
            if (($operation != 'create') && !($this->_tableExists($table))) {
                continue;
            }
            if (!$this->_tableExists($refTable)) {
                continue;
            }

            // Self references are out of our fix scope
            if ($refTable == $table) {
                continue;
            }

            // Extract column type
            if ($operation == 'create') {
                $columnDefinition = $this->_getColumnDefinitionFromSql($sql, $column);
            } else {
                $columnDefinition = $this->_getColumnDefinitionFromTable($table, $column);
            }

            // We fix only int columns
            if (!$columnDefinition || !in_array($columnDefinition['type'], $this->_processedTypes)) {
                continue;
            }

            // Extract referenced column type
            $refColumnDefinition = $this->_getColumnDefinitionFromTable($refTable, $refColumn);
            if (!$refColumnDefinition) {
                continue;
            }

            // We fix only int columns
            if (!$refColumnDefinition || !in_array($refColumnDefinition['type'], $this->_processedTypes)) {
                continue;
            }

            // Whether we need to fix
            if ($refColumnDefinition == $columnDefinition) {
                continue;
            }

            // Fix column to be the same type as referenced one
            if ($operation == 'create') {
                $this->_fixColumnDefinitionInSql($sql, $column, $refColumnDefinition);
            } else {
                $this->_fixColumnDefinitionInTable($table, $column, $refColumnDefinition);
            }
        }

        return $this;
    }
}
