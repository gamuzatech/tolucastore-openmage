<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Bundle
 */

/**
 * Bundle selection renderer
 *
 * @package    Mage_Bundle
 *
 * @method bool getCanEditPrice()
 * @method $this setCanEditPrice(bool $value)
 * @method bool getCanReadPrice()
 * @method $this setCanReadPrice(bool $value)
 */
class Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection extends Mage_Adminhtml_Block_Widget
{
    /**
     * Initialize bundle option selection block
     */
    public function __construct()
    {
        $this->setTemplate('bundle/product/edit/bundle/option/selection.phtml');
        $this->setCanReadPrice(true);
        $this->setCanEditPrice(true);
    }

    /**
     * Return field id
     *
     * @return string
     */
    public function getFieldId()
    {
        return 'bundle_selection';
    }

    /**
     * Return field name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'bundle_selections';
    }

    /**
     * Prepare block layout
     *
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setChild(
            'selection_delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData([
                    'label' => Mage::helper('catalog')->__('Delete'),
                    'class' => 'delete icon-btn',
                    'on_click' => 'bSelection.remove(event)',
                ]),
        );
        return parent::_prepareLayout();
    }

    /**
     * Retrieve delete button html
     *
     * @return string
     */
    public function getSelectionDeleteButtonHtml()
    {
        return $this->getChildHtml('selection_delete_button');
    }

    /**
     * Retrieve price type select html
     *
     * @return string
     */
    public function getPriceTypeSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData([
                'id'    => $this->getFieldId() . '_{{index}}_price_type',
                'class' => 'select select-product-option-type required-option-select',
            ])
            ->setName($this->getFieldName() . '[{{parentIndex}}][{{index}}][selection_price_type]')
            ->setOptions(Mage::getSingleton('bundle/source_option_selection_price_type')->toOptionArray());
        if ($this->getCanEditPrice() === false) {
            $select->setExtraParams('disabled="disabled"');
        }
        return $select->getHtml();
    }

    /**
     * Retrieve qty type select html
     *
     * @return string
     */
    public function getQtyTypeSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData([
                'id' => $this->getFieldId() . '_{{index}}_can_change_qty',
                'class' => 'select',
            ])
            ->setName($this->getFieldName() . '[{{parentIndex}}][{{index}}][selection_can_change_qty]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }

    /**
     * Return search url
     *
     * @return string
     */
    public function getSelectionSearchUrl()
    {
        return $this->getUrl('*/bundle_selection/search');
    }

    /**
     * Check if used website scope price
     *
     * @return bool
     */
    public function isUsedWebsitePrice()
    {
        return !Mage::helper('catalog')->isPriceGlobal() && Mage::registry('product')->getStoreId();
    }

    /**
     * Retrieve price scope checkbox html
     *
     * @return string
     */
    public function getCheckboxScopeHtml()
    {
        $checkboxHtml = '';
        if ($this->isUsedWebsitePrice()) {
            $id = $this->getFieldId() . '_{{index}}_price_scope';
            $name = $this->getFieldName() . '[{{parentIndex}}][{{index}}][default_price_scope]';
            $class = 'bundle-option-price-scope-checkbox';
            $label = Mage::helper('bundle')->__('Use Default Value');
            $disabled = ($this->getCanEditPrice() === false) ? ' disabled="disabled"' : '';
            $checkboxHtml = '<input type="checkbox" id="' . $id . '" class="' . $class . '" name="' . $name
                . '"' . $disabled . ' value="1" />';
            $checkboxHtml .= '<label class="normal" for="' . $id . '">' . $label . '</label>';
        }
        return $checkboxHtml;
    }
}
