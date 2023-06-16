<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml customers groups grid block
 */
class Gamuza_Basic_Block_Adminhtml_Customer_Group_Grid
    extends Mage_Adminhtml_Block_Customer_Group_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('customer_group_id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        $this->getColumn ('type')
            ->setHeader (Mage::helper('basic')->__('Code'))
        ;

        $this->addColumnAfter('name', array(
            'header' => Mage::helper('basic')->__('Name'),
            'index'  => 'name',
        ), 'type');

        $this->addColumnAfter('is_system', array(
            'header'  => Mage::helper('basic')->__('System'),
            'index'   => 'is_system',
            'width'   => '200px',
            'type'    => 'options',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
        ), 'name');

        $this->sortColumnsByOrder ();

        return $result;
    }

    public function getRowUrl($row)
    {
        if (!$row->getIsSystem())
        {
            return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
        }
    }
}

