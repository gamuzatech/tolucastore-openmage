<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Adminhtml additional helper block for sort by
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Catalog_Category_Helper_Sortby_Default extends Varien_Data_Form_Element_Select
{
    /**
     * Returns js code that is used instead of default toggle code for "Use default config" checkbox
     *
     * @return string
     */
    public function getToggleCode()
    {
        $htmlId = 'use_config_' . $this->getHtmlId();
        return 'toggleValueElements(this, this.parentNode.parentNode);'
            . "if (!this.checked) toggleValueElements($('$htmlId'), $('$htmlId').parentNode);";
    }

    /**
     * Retrieve Element HTML fragment
     *
     * @return string
     */
    public function getElementHtml()
    {
        $elementDisabled = $this->getDisabled() == 'disabled';
        $disabled = false;

        if (!$this->getValue() || $elementDisabled) {
            $this->setValue(Mage::getStoreConfig(Mage_Catalog_Model_Config::XML_PATH_LIST_DEFAULT_SORT_BY));
            $this->setData('disabled', 'disabled');
            $disabled = true;
        }

        $html = parent::getElementHtml();
        $htmlId = 'use_config_' . $this->getHtmlId();
        $html .= '<input id="' . $htmlId . '" name="use_config[]" value="' . $this->getId() . '"';
        $html .= ($disabled ? ' checked="checked"' : '');

        if ($this->getReadonly() || $elementDisabled) {
            $html .= ' disabled="disabled"';
        }

        $html .= ' onclick="toggleValueElements(this, this.parentNode);" class="checkbox" type="checkbox" />';
        $html .= ' <label for="' . $htmlId . '" class="normal">'
            . Mage::helper('adminhtml')->__('Use Config Settings') . '</label>';

        return $html . ('<script type="text/javascript">toggleValueElements($(\'' . $htmlId . '\'), $(\'' . $htmlId
            . '\').parentNode);</script>');
    }
}
