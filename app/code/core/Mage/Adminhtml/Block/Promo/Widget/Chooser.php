<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Shopping cart price rule chooser
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Promo_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block constructor, prepare grid params
     *
     * @param array $arguments
     */
    public function __construct($arguments = [])
    {
        parent::__construct($arguments);
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('*/promo_quote/chooser', ['uniq_id' => $uniqId]);

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);

        if ($element->getValue()) {
            $rule = Mage::getModel('salesrule/rule')->load((int) $element->getValue());
            if ($rule->getId()) {
                $chooser->setLabel($rule->getName());
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        return '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var ruleName = trElement.down("td").next().innerHTML;
                var ruleId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                ' . $chooserJsObject . '.setElementValue(ruleId);
                ' . $chooserJsObject . '.setElementLabel(ruleName);
                ' . $chooserJsObject . '.close();
            }
        ';
    }

    /**
     * Prepare rules collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesrule/rule')->getResourceCollection();
        $this->setCollection($collection);

        Mage::dispatchEvent('adminhtml_block_promo_widget_chooser_prepare_collection', [
            'collection' => $collection,
        ]);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for rules grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', [
            'header'    => Mage::helper('salesrule')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'rule_id',
        ]);

        $this->addColumn('name', [
            'header'    => Mage::helper('salesrule')->__('Rule Name'),
            'align'     => 'left',
            'index'     => 'name',
        ]);

        $this->addColumn('coupon_code', [
            'header'    => Mage::helper('salesrule')->__('Coupon Code'),
            'align'     => 'left',
            'width'     => '150px',
            'index'     => 'code',
        ]);

        $this->addColumn('from_date', [
            'header'    => Mage::helper('salesrule')->__('Date Start'),
            'align'     => 'left',
            'type'      => 'date',
            'index'     => 'from_date',
        ]);

        $this->addColumn('to_date', [
            'header'    => Mage::helper('salesrule')->__('Date Expire'),
            'align'     => 'left',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'to_date',
        ]);

        $this->addColumn('is_active', [
            'header'    => Mage::helper('salesrule')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => [
                1 => Mage::helper('salesrule')->__('Active'),
                0 => Mage::helper('salesrule')->__('Inactive'),
            ],
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/promo_quote/chooser', ['_current' => true]);
    }
}
