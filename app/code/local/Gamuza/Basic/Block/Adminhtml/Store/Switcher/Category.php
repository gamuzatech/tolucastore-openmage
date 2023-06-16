<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Store switcher category block
 */
class Gamuza_Basic_Block_Adminhtml_Store_Switcher_Category
    extends Mage_Adminhtml_Block_Store_Switcher
{
    /**
     * Name of store variable
     *
     * @var string
     */
    protected $_categoryVarName = 'category';

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('gamuza/basic/store/switcher/category.phtml');
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        $result = array();

        $collection = Mage::getModel('catalog/category')->getCollection()
            ->addIsActiveFilter()
            ->addNameToResult()
            ->setOrder('level')
            ->setOrder('position');

        foreach ($collection as $category)
        {
            $result[$category->getId()] = sprintf('%s %s', str_repeat('-', $category->getLevel()), $category->getName());
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getSwitchUrl()
    {
        if ($url = $this->getData('switch_url'))
        {
            return $url;
        }

        return $this->getUrl('*/*/*', ['_current' => true, $this->_categoryVarName => null]);
    }

    /**
     * @param string $varName
     * @return $this
     */
    public function setCategoryVarName($varName)
    {
        $this->_categoryVarName = $varName;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCategoryId()
    {
        return $this->getRequest()->getParam($this->_categoryVarName);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return Mage_Adminhtml_Block_Template::_toHtml();
    }
}

