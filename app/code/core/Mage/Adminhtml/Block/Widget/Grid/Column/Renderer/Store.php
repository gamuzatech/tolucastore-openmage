<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Store grid column filter
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Store extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_skipAllStoresLabel = false;
    protected $_skipEmptyStoresLabel = false;

    /**
     * Retrieve System Store model
     *
     * @return Mage_Adminhtml_Model_System_Store
     */
    protected function _getStoreModel()
    {
        return Mage::getSingleton('adminhtml/system_store');
    }

    /**
     * Retrieve 'show all stores label' flag
     *
     * @return bool
     */
    protected function _getShowAllStoresLabelFlag()
    {
        return $this->getColumn()->getData('skipAllStoresLabel')
            ? $this->getColumn()->getData('skipAllStoresLabel')
            : $this->_skipAllStoresLabel;
    }

    /**
     * Retrieve 'show empty stores label' flag
     *
     * @return bool
     */
    protected function _getShowEmptyStoresLabelFlag()
    {
        return $this->getColumn()->getData('skipEmptyStoresLabel')
            ? $this->getColumn()->getData('skipEmptyStoresLabel')
            : $this->_skipEmptyStoresLabel;
    }

    /**
     * Render row store views
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $out = '';
        $skipAllStoresLabel = $this->_getShowAllStoresLabelFlag();
        $skipEmptyStoresLabel = $this->_getShowEmptyStoresLabelFlag();
        $origStores = $row->getData($this->getColumn()->getIndex());

        if (is_null($origStores) && $row->getStoreName()) {
            $scopes = [];
            foreach (explode("\n", $row->getStoreName()) as $k => $label) {
                $scopes[] = str_repeat('&nbsp;', $k * 3) . $label;
            }
            return $out . (implode('<br/>', $scopes) . $this->__(' [deleted]'));
        }

        if (empty($origStores) && !$skipEmptyStoresLabel) {
            return '';
        }
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }

        if (empty($origStores)) {
            return '';
        } elseif (in_array(0, $origStores) && count($origStores) == 1 && !$skipAllStoresLabel) {
            return Mage::helper('adminhtml')->__('All Store Views');
        }

        $data = $this->_getStoreModel()->getStoresStructure(false, $origStores);

        foreach ($data as $website) {
            $out .= Mage::helper('core')->escapeHtml($website['label']) . '<br/>';
            foreach ($website['children'] as $group) {
                $out .= str_repeat('&nbsp;', 3) . Mage::helper('core')->escapeHtml($group['label']) . '<br/>';
                foreach ($group['children'] as $store) {
                    $out .= str_repeat('&nbsp;', 6) . Mage::helper('core')->escapeHtml($store['label']) . '<br/>';
                }
            }
        }

        return $out;
    }

    /**
     * Render row store views for export
     *
     * @return string
     */
    public function renderExport(Varien_Object $row)
    {
        $out = '';
        $skipAllStoresLabel = $this->_getShowAllStoresLabelFlag();
        $origStores = $row->getData($this->getColumn()->getIndex());

        if (is_null($origStores) && $row->getStoreName()) {
            $scopes = [];
            foreach (explode("\n", $row->getStoreName()) as $k => $label) {
                $scopes[] = str_repeat(' ', $k * 3) . $label;
            }
            return $out . (implode("\r\n", $scopes) . $this->__(' [deleted]'));
        }

        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }

        if (in_array(0, $origStores) && !$skipAllStoresLabel) {
            return Mage::helper('adminhtml')->__('All Store Views');
        }

        $data = $this->_getStoreModel()->getStoresStructure(false, $origStores);

        foreach ($data as $website) {
            $out .= $website['label'] . "\r\n";
            foreach ($website['children'] as $group) {
                $out .= str_repeat(' ', 3) . $group['label'] . "\r\n";
                foreach ($group['children'] as $store) {
                    $out .= str_repeat(' ', 6) . $store['label'] . "\r\n";
                }
            }
        }

        return $out;
    }
}
