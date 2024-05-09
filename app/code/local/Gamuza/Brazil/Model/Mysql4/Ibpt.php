<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Mysql4_Ibpt extends Mage_Core_Model_Mysql4_Abstract
{
    public const COLUMN_DELIMITER = ';';

    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors = [];

    /**
     * Count of imported rows
     *
     * @var int
     */
    protected $_importedRows = 0;

    protected function _construct ()
    {
        $this->_init ('brazil/ibpt', 'entity_id');
    }

    /**
     * Upload IBPT file and import data from it
     */
    public function uploadAndImport (Varien_Object $object)
    {
        if (empty ($_FILES ['groups']['tmp_name']['ibpt']['fields']['import']['value']))
        {
            return $this;
        }

        $csvFile = $_FILES ['groups']['tmp_name']['ibpt']['fields']['import']['value'];

        $this->_importUniqueHash = [];
        $this->_importErrors     = [];
        $this->_importedRows     = 0;

        $info = pathinfo ($csvFile);

        $io = new Varien_Io_File ();
        $io->open (['path' => $info ['dirname']]);
        $io->streamOpen ($info ['basename'], 'r');

        // check and skip headers
        $headers = $io->streamReadCsv (self::COLUMN_DELIMITER);

        if ($headers === false || count ($headers) < 13)
        {
            $io->streamClose ();

            Mage::throwException (Mage::helper ('brazil')->__('Invalid IBPT File Format'));
        }

        $adapter = $this->_getWriteAdapter ();
        $adapter->beginTransaction ();

        try {
            $rowNumber  = 1;
            $importData = [];

            // delete old data
            $condition = [];
            $adapter->delete ($this->getMainTable (), $condition);
            $adapter->truncateTable ($this->getMainTable ());

            while (($csvLine = $io->streamReadCsv (self::COLUMN_DELIMITER)) !== false)
            {
                $rowNumber ++;

                if (empty ($csvLine))
                {
                    continue;
                }

                $row = $this->_getImportRow ($csvLine, $rowNumber, $headers);

                if ($row !== false)
                {
                    $importData [] = $row;
                }

                if (count ($importData) == 5000)
                {
                    $this->_saveImportData ($importData);

                    $importData = [];
                }
            }

            $this->_saveImportData ($importData);

            $io->streamClose ();

            $adapter->commit ();
        }
        catch (Mage_Core_Exception $e)
        {
            $adapter->rollBack ();

            $io->streamClose ();

            Mage::throwException ($e->getMessage ());
        }
        catch (Exception $e)
        {
            $adapter->rollBack ();

            $io->streamClose ();

            Mage::logException ($e);
            Mage::throwException (Mage::helper ('brazil')->__('An error occurred while import IBPT.') . PHP_EOL . $e->getMessage ());
        }

        if ($this->_importErrors)
        {
            $error = Mage::helper ('brazil')->__('File has not been imported. See the following list of errors: %s', PHP_EOL . implode (PHP_EOL, $this->_importErrors));

            Mage::throwException ($error);
        }

        Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('brazil')->__('The IBPT file has been imported successfully!'));

        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_BEGIN_AT, $row [8]); // begin_at
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_END_AT,   $row [9]); // end_at
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_KEY,      $row [10]); // key
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_VERSION,  $row [11]); // version
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_SOURCE,   $row [12]); // source

        return $this;
    }

    /**
     * Validate row for import and return IBPT array or false
     * Error will be add to _importErrors array
     *
     * @param array $row
     * @param int $rowNumber
     * @param array $headers
     * @return array|false
     */
    protected function _getImportRow ($row, $rowNumber = 0, $headers)
    {
        // validate row
        if (count ($row) < 13)
        {
            $this->_importErrors [] = Mage::helper ('brazil')->__('Invalid IBTP format in the Row #%s', $rowNumber);

            return false;
        }

        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v)
        {
            $row [$k] = trim ($v);

            if (empty ($v) && !ctype_digit ($v))
            {
                $row [$k] = null;
            }
        }

        // validate type
        $value = $this->_parseIntegerValue ($row [2]);

        if ($value === false)
        {
            $this->_importErrors [] = Mage::helper ('brazil')->__("Invalid %s '%s' in the Row #%s.", $headers [2], $row [2], $rowNumber);
        }

        // validate national_federal
        $value = $this->_parseDecimalValue ($row [4]);

        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [4], $row [4], $rowNumber);

            return false;
        }

        // validate imported_federal
        $value = $this->_parseDecimalValue ($row [5]);

        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [5], $row [5], $rowNumber);

            return false;
        }

        // validate state
        $value = $this->_parseDecimalValue ($row [6]);

        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [6], $row [6], $rowNumber);

            return false;
        }

        // validate local
        $value = $this->_parseDecimalValue ($row [7]);

        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [7], $row [7], $rowNumber);

            return false;
        }

        $row [8] = $this->_convertDate ($row [8]); // begin_at
        $row [9] = $this->_convertDate ($row [9]); // end_at

        $row [13] = date ('c'); // created_at

        return $row;
    }

    /**
     * Save import data batch
     *
     * @param array $data
     * @return $this
     */
    protected function _saveImportData (array $data)
    {
        if (!empty ($data))
        {
            $columns = [
                'code', 'exception', 'type', 'description',
                'national_federal', 'imported_federal', 'state', 'local',
                'begin_at', 'end_at', 'key', 'version', 'source',
                'created_at',
            ];

            $this->_getWriteAdapter ()->insertArray ($this->getMainTable (), $columns, $data);

            $this->_importedRows += count ($data);
        }

        return $this;
    }

    /**
     * Parse and validate positive integer value
     * Return false if value is not integer or is not positive
     *
     * @param string $value
     * @return bool|int
     */
    protected function _parseIntegerValue ($value)
    {
        if (!ctype_digit ($value))
        {
            return false;
        }

        $value = (int) sprintf ('%.9u', $value);

        if ($value < 0)
        {
            return false;
        }

        return $value;
    }

    /**
     * Parse and validate positive decimal value
     * Return false if value is not decimal or is not positive
     *
     * @param string $value
     * @return bool|float
     */
    protected function _parseDecimalValue ($value)
    {
        if (!is_numeric ($value))
        {
            return false;
        }

        $value = (float) sprintf ('%.4F', $value);

        if ($value < 0.0000)
        {
            return false;
        }

        return $value;
    }

    /**
     * Convert given date to UTC
     */
    protected function _convertDate ($date)
    {
        $defaultLocale = Mage::getStoreConfig (Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);

        $locale = new Zend_Locale ($defaultLocale);

        $date = Mage::app ()->getLocale ()->date ($date, null, $locale, false);

        return $date->toString ('YYYY-MM-dd');
    }
}

