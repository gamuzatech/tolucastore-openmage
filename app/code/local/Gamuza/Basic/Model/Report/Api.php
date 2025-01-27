<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Basic Report API
 */
class Gamuza_Basic_Model_Report_Api extends Mage_Core_Model_Magento_Api
{
    public function accounting($types = array(), $from, $to, $locale)
    {
        if (empty($types))
        {
            $this->_fault('types_not_specified');
        }

        if (empty($from) || empty($to))
        {
            $this->_fault('range_not_specified');
        }

        if (empty($locale))
        {
            $this->_fault('locale_not_specified');
        }

        $defaultFilter = array(
            'created_at' => array(
                'from' => $from,
                'to' => $to,
                'locale' => $locale,
            ),
        );

        $result = array(
            'csv' => array(),
        );

        foreach ($types as $type)
        {
            $blockName = null;

            switch ($type)
            {
                case 'order':
                {
                    $blockName = 'basic/adminhtml_sales_order_grid';

                    $defaultFilter['state_color'] = Mage_Sales_Model_Order::STATE_COMPLETE;

                    break;
                }
                case 'pdv_history':
                {
                    $blockName = 'pdv/adminhtml_history_grid';

                    break;
                }
                case 'pdv_log':
                {
                    $blockName = 'pdv/adminhtml_log_grid';

                    break;
                }
                case 'brazil_nfce':
                {
                    $blockName = 'brazil/adminhtml_nfce_grid';

                    $defaultFilter['status_id'] = Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED;

                    break;
                }
                case 'brazil_nfe':
                {
                    $blockName = 'brazil/adminhtml_nfe_grid';

                    $defaultFilter['status_id'] = Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED;

                    break;
                }
                default:
                {
                    $this->_fault('type_not_exists');

                    break;
                }
            }

            $grid = Mage::app()->getLayout()->createBlock($blockName)
                ->setDefaultFilter($defaultFilter)
            ;

            $csvFile = $grid->getCsvFile();
            $csvFile['name']  = sprintf('%s.csv', $type);
            $csvFile['count'] = $grid->getCollection()->getSize();

            $result['csv'][] = $csvFile;

            /**
             * Brazil XMLs
             */
            switch ($type)
            {
                case 'brazil_nfce':
                case 'brazil_nfe':
                {
                    $type = str_replace('brazil_', "", $type);

                    foreach ($grid->getCollection() as $item)
                    {
                        $xmlDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . $type . DS . 'response' . DS . 'info';

                        $xmlFile = sprintf ('%s%s%s-%s-%s.xml', $xmlDir, DS, $item->getIncrementId (), $item->getProtectCode (), $item->getKey ());

                        if (!is_file ($xmlFile))
                        {
                            $xmlFile = sprintf ('%s%s%s-%s-%s-%s-%s.xml', $xmlDir, DS, $item->getIncrementId (), $item->getProtectCode (), $type, $item->getNumberId (), $item->getKey ());
                        }

                        $result['csv'][] = array(
                            'value' => $xmlFile,
                        );
                    }

                    break;
                }
            }
        }

        return $result;
    }
}

