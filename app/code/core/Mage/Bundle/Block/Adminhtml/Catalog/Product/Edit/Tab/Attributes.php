<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Bundle
 */

/**
 * Bundle product attributes tab
 *
 * @package    Mage_Bundle
 *
 * @method bool getCanEditPrice()
 */
class Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    /**
     * Prepare attributes form of bundle product
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();

        $specialPrice = $this->getForm()->getElement('special_price');
        if ($specialPrice) {
            $specialPrice->setRenderer(
                $this->getLayout()->createBlock('bundle/adminhtml_catalog_product_edit_tab_attributes_special')
                    ->setDisableChild(false),
            );
        }

        $sku = $this->getForm()->getElement('sku');
        if ($sku) {
            $sku->setRenderer(
                $this->getLayout()->createBlock('bundle/adminhtml_catalog_product_edit_tab_attributes_extend')
                    ->setDisableChild(false),
            );
        }

        $price = $this->getForm()->getElement('price');
        if ($price) {
            $price->setRenderer(
                $this->getLayout()->createBlock(
                    'bundle/adminhtml_catalog_product_edit_tab_attributes_extend',
                    'adminhtml.catalog.product.bundle.edit.tab.attributes.price',
                )->setDisableChild(true),
            );
        }

        $tax = $this->getForm()->getElement('tax_class_id');
        if ($tax) {
            $tax->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                //<![CDATA[
                function changeTaxClassId() {
                    if ($('price_type').value == '" . Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC . "') {
                        $('tax_class_id').disabled = true;
                        $('tax_class_id').value = '0';
                        $('tax_class_id').removeClassName('required-entry');
                        if ($('advice-required-entry-tax_class_id')) {
                            $('advice-required-entry-tax_class_id').remove();
                        }
                    } else {
                        $('tax_class_id').disabled = false;
                        " . ($tax->getRequired() ? "$('tax_class_id').addClassName('required-entry');" : '') . "
                    }
                }

                document.observe('dom:loaded', function() {
                    if ($('price_type')) {
                        $('price_type').observe('change', changeTaxClassId);
                        changeTaxClassId();
                    }
                });
                //]]>
                "
                . '</script>',
            );
        }

        $weight = $this->getForm()->getElement('weight');
        if ($weight) {
            $weight->setRenderer(
                $this->getLayout()->createBlock('bundle/adminhtml_catalog_product_edit_tab_attributes_extend')
                    ->setDisableChild(true),
            );
        }

        $tierPrice = $this->getForm()->getElement('tier_price');
        if ($tierPrice) {
            $tierPrice->setRenderer(
                $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_price_tier')
                    ->setPriceColumnHeader(Mage::helper('bundle')->__('Percent Discount'))
                    ->setPriceValidation('validate-greater-than-zero validate-percents'),
            );
        }

        $groupPrice = $this->getForm()->getElement('group_price');
        if ($groupPrice) {
            $groupPrice->setRenderer(
                $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_price_group')
                    ->setPriceColumnHeader(Mage::helper('bundle')->__('Percent Discount'))
                    ->setIsPercent(true)
                    ->setPriceValidation('validate-greater-than-zero validate-percents'),
            );
        }

        $mapEnabled = $this->getForm()->getElement('msrp_enabled');
        if ($mapEnabled && $this->getCanEditPrice() !== false) {
            $mapEnabled->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                function changePriceTypeMap() {
                    if ($('price_type').value == " . Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC . ") {
                        $('msrp_enabled').setValue("
                        . Mage_Catalog_Model_Product_Attribute_Source_Msrp_Type_Enabled::MSRP_ENABLE_NO
                        . ");
                        $('msrp_enabled').disable();
                        $('msrp_display_actual_price_type').setValue("
                        . Mage_Catalog_Model_Product_Attribute_Source_Msrp_Type_Price::TYPE_USE_CONFIG
                        . ");
                        $('msrp_display_actual_price_type').disable();
                        $('msrp').setValue('');
                        $('msrp').disable();
                    } else {
                        $('msrp_enabled').enable();
                        $('msrp_display_actual_price_type').enable();
                        $('msrp').enable();
                    }
                }
                document.observe('dom:loaded', function() {
                    $('price_type').observe('change', changePriceTypeMap);
                    changePriceTypeMap();
                });
                "
                . '</script>',
            );
        }

        return $this;
    }

    /**
     * Get current product from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->getData('product')) {
            $this->setData('product', Mage::registry('product'));
        }
        return $this->getData('product');
    }
}
