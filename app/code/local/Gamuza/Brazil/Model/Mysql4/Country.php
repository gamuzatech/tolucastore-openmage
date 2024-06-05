<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Mysql4_Country extends Mage_Core_Model_Mysql4_Abstract
{
    const HEADER_DELIMITER = ',';
    const LINE_DELIMITER   = '|';

    const ROW_COUNT = 5000;

    const VERSION_REGEX = '/versão=(.*)COD_PAIS/';

    const ENCODING_TO   = 'UTF-8';
    const ENCODING_FROM = 'ISO-8859-15';

    const DATE_FORMAT     = 'ddMMYYYY';
    const DATETIME_FORMAT = 'YYYY-MM-dd HH:mm:ss';

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
        $this->_init ('brazil/country', 'entity_id');
    }

    /**
     * Upload Countries file and import data from it
     */
    public function uploadAndImport (Varien_Object $object)
    {
        if (empty ($_FILES ['groups']['tmp_name']['country']['fields']['import']['value']))
        {
            return $this;
        }

        $csvFile = $_FILES ['groups']['tmp_name']['country']['fields']['import']['value'];

        $this->_importErrors = [];
        $this->_importedRows = 0;

        $info = pathinfo ($csvFile);

        $io = new Varien_Io_File ();
        $io->open (['path' => $info ['dirname']]);
        $io->streamOpen ($info ['basename'], 'r');

        // check and skip headers
        $headers = $io->streamReadCsv (self::HEADER_DELIMITER);

        if ($headers === false || count ($headers) < 4)
        {
            $io->streamClose ();

            Mage::throwException (Mage::helper ('brazil')->__('Invalid Countries File Format'));
        }

        $matches = array ();
        $version = mb_convert_encoding ($headers [0], self::ENCODING_TO, self::ENCODING_FROM);
        $beginAt = null;
        $endAt = null;

        if (preg_match (self::VERSION_REGEX, $version, $matches) != 1 || !ctype_digit (trim ($matches [1])))
        {
            $io->streamClose ();

            Mage::throwException (Mage::helper ('brazil')->__('Invalid Countries File Format'));
        }
        else
        {
            $version = trim ($matches [1]);
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

            while (($csvLine = $io->streamReadCsv (self::LINE_DELIMITER)) !== false)
            {
                $rowNumber ++;

                if (empty ($csvLine))
                {
                    continue;
                }

                $row = $this->_getImportRow ($csvLine, $rowNumber, $headers, $version, $beginAt, $endAt);

                if ($row !== false)
                {
                    $importData [] = $row;
                }

                if (count ($importData) == self::ROW_COUNT)
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
            Mage::throwException (Mage::helper ('brazil')->__('An error occurred while import Countries.') . PHP_EOL . $e->getMessage ());
        }

        if ($this->_importErrors)
        {
            $error = Mage::helper ('brazil')->__('File has not been imported. See the following list of errors: %s', PHP_EOL . implode (PHP_EOL, $this->_importErrors));

            Mage::throwException ($error);
        }

        Mage::getSingleton ('adminhtml/session')->addSuccess (Mage::helper ('brazil')->__('The Countries file has been imported successfully!'));

        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_COUNTRY_VERSION,  $version);
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_COUNTRY_BEGIN_AT, $beginAt);
        Mage::getModel ('core/config')->saveConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_COUNTRY_END_AT,   $endAt);

        return $this;
    }

    /**
     * Validate row for import and return Countries array or false
     * Error will be add to _importErrors array
     *
     * @param array $row
     * @param int $rowNumber
     * @param array $headers
     * @return array|false
     */
    protected function _getImportRow ($row, $rowNumber, $headers, $version, & $beginAt, & $endAt)
    {
        // validate row
        if (count ($row) < 4)
        {
            $this->_importErrors [] = Mage::helper ('brazil')->__('Invalid Countries format in the Row #%s', $rowNumber);

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

        // validate code
        $value = $this->_parseIntegerValue ($row [0]);

        if ($value === false)
        {
            $this->_importErrors [] = Mage::helper ('brazil')->__("Invalid %s '%s' in the Row #%s.", $headers [0], $row [0], $rowNumber);

            return false;
        }

        if (empty ($row [1]))
        {
            $this->_importErrors [] = Mage::helper ('brazil')->__("Invalid %s '%s' in the Row #%s.", $headers [1], $row [1], $rowNumber);

            return false;
        }

        // validate begin_at
        $value = $this->_parseIntegerValue ($row [2]);

        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [2], $row [2], $rowNumber);

            return false;
        }

        // validate end_at
        $value = $this->_parseIntegerValue ($row [3]);
/*
        if ($value === false)
        {
            $this->_importErrors[] = Mage::helper ('brazil')->__('Invalid %s "%s" in the Row #%s.', $headers [3], $row [3], $rowNumber);

            return false;
        }
*/
        $row [1] = mb_convert_encoding ($row [1], self::ENCODING_TO, self::ENCODING_FROM); // description
        $row [2] = $this->_convertDate (str_pad ($row [2], 8, '0', STR_PAD_LEFT)); // begin_at
        $row [3] = $value === false ? null : $this->_convertDate ($row [3], 86400 - 1); // end_at
/*
        $now = time ();

        if (strtotime ($row [2]) > $now || strtotime ($row [3]) < $now)
        {
            Mage::throwException (Mage::helper ('brazil')->__('Requested Countries table is not valid.'));
        }
*/
        if ($beginAt == null || strtotime ($beginAt) > strtotime ($row [2]))
        {
            $beginAt = $row [2];
        }

        if ($endAt == null || strtotime ($endAt) < strtotime ($row [3]))
        {
            $endAt = $row [3];
        }

        $row [4] = $version;
        $row [5] = date ('c'); // created_at

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
                'code', 'description', 'begin_at', 'end_at',
                'version', 'created_at',
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

        $value = (int) sprintf ('%.10u', $value);

        if ($value < 0)
        {
            return false;
        }

        return $value;
    }

    /**
     * Convert given date to UTC
     */
    protected function _convertDate ($date, $second = 0)
    {
        $store = Mage_Core_Model_App::ADMIN_STORE_ID;

        $utcDate = Mage::app ()->getLocale ()->utcDate ($store, $date, true, self::DATE_FORMAT);

        $utcDate->addSecond ($second);

        return $utcDate->toString (self::DATETIME_FORMAT);
    }
}

