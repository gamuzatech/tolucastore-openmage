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
                    $blockName = 'adminhtml/sales_order_grid';

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

                    break;
                }
                case 'brazil_nfe':
                {
                    $blockName = 'brazil/adminhtml_nfe_grid';

                    break;
                }
                default:
                {
                    $this->_fault('type_not_exists');

                    break;
                }
            }

            $grid = Mage::app()->getLayout()->createBlock($blockName)
                ->setDefaultFilter(
                    array(
                        'created_at' => array(
                            'from' => $from,
                            'to' => $to,
                            'locale' => $locale,
                        ),
                    ),
                )
            ;

            $csvFile = $grid->getCsvFile();
            $csvFile['name']  = sprintf('%s.csv', $type);
            $csvFile['count'] = $grid->getCollection()->getSize();

            $result['csv'][] = $csvFile;
        }

        return $result;
    }
}

