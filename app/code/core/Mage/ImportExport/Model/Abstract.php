<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_ImportExport
 */

/**
 * Operation abstract class
 *
 * @package    Mage_ImportExport
 *
 * @method string getRunAt()
 * @method int getScheduledOperationId()
 * @method string getOperationType()
 */
abstract class Mage_ImportExport_Model_Abstract extends Varien_Object
{
    /**
     * Log directory
     *
     */
    public const LOG_DIRECTORY = 'log/import_export/';

    /**
     * Enable logging
     *
     * @var bool
     */
    protected $_debugMode = false;

    /**
     * Logger instance
     * @var Mage_Core_Model_Log_Adapter
     */
    protected $_logInstance;

    /**
     * Fields that should be replaced in debug with '***'
     *
     * @var array
     */
    protected $_debugReplacePrivateDataKeys = [];

    /**
     * Contains all log information
     *
     * @var array
     */
    protected $_logTrace = [];

    /**
     * Log debug data to file.
     * Log file dir: var/log/import_export/%Y/%m/%d/%time%_%operation_type%_%entity_type%.log
     *
     * @param mixed $debugData
     * @return Mage_ImportExport_Model_Abstract
     */
    public function addLogComment($debugData)
    {
        if (is_array($debugData)) {
            $this->_logTrace = array_merge($this->_logTrace, $debugData);
        } else {
            $this->_logTrace[] = $debugData;
        }
        if (!$this->_debugMode) {
            return $this;
        }

        if (!$this->_logInstance) {
            $dirName  = date('Y' . DS . 'm' . DS . 'd' . DS);
            $fileName = implode('_', [
                str_replace(':', '-', $this->getRunAt()),
                $this->getScheduledOperationId(),
                $this->getOperationType(),
                $this->getEntity(),
            ]);
            $dirPath = Mage::getBaseDir('var') . DS . self::LOG_DIRECTORY
                . $dirName;
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0750, true);
            }
            $fileName = substr(strstr(self::LOG_DIRECTORY, DS), 1)
                . $dirName . $fileName . '.log';
            $this->_logInstance = Mage::getModel('core/log_adapter', $fileName)
                ->setFilterDataKeys($this->_debugReplacePrivateDataKeys);
        }
        $this->_logInstance->log($debugData);
        return $this;
    }

    /**
     * Return human readable debug trace.
     *
     * @return string
     */
    public function getFormatedLogTrace()
    {
        $trace = '';
        $lineNumber = 1;
        foreach ($this->_logTrace as &$info) {
            $trace .= $lineNumber++ . ': ' . $info . "\n";
        }
        return $trace;
    }

    /**
     * Sets debug mode
     *
     * @param bool $mode
     * @return Mage_ImportExport_Model_Abstract
     */
    public function setDebugMode($mode = true)
    {
        $this->_debugMode = (bool) $mode;
        return $this;
    }
}
