<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * REgion field renderer
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_Customer_Renderer_Region implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Country region collections
     *
     * array(
     *      [$countryId] => Varien_Data_Collection_Db
     * )
     *
     * @var array
     */
    protected static $_regionCollections;

    /**
     * @return string
     * @SuppressWarnings("PHPMD.CamelCaseVariableName")
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<tr>' . "\n";

        $countryId = false;
        if ($country = $element->getForm()->getElement('country_id')) {
            $countryId = $country->getValue();
        }

        $regionCollection = false;
        if ($countryId) {
            if (!isset(self::$_regionCollections[$countryId])) {
                self::$_regionCollections[$countryId] = Mage::getModel('directory/country')
                    ->setId($countryId)
                    ->getLoadedRegionCollection()
                    ->toOptionArray();
            }
            $regionCollection = self::$_regionCollections[$countryId];
        }

        $regionId = (int) $element->getForm()->getElement('region_id')->getValue();

        $htmlAttributes = $element->getHtmlAttributes();
        foreach ($htmlAttributes as $key => $attribute) {
            if ($attribute === 'type') {
                unset($htmlAttributes[$key]);
                break;
            }
        }

        // Output two elements - for 'region' and for 'region_id'.
        // Two elements are needed later upon form post - to properly set data to address model,
        // otherwise old value can be left in region_id attribute and saved to DB.
        // Depending on country selected either 'region' (input text) or 'region_id' (selectbox) is visible to user
        $regionHtmlName = $element->getName();
        $regionIdHtmlName = str_replace('region', 'region_id', $regionHtmlName);
        $regionHtmlId = $element->getHtmlId();
        $regionIdHtmlId = str_replace('region', 'region_id', $regionHtmlId);

        if ($regionCollection && count($regionCollection) > 0) {
            $elementClass = $element->getClass();
            $html .= '<td class="label">' . $element->getLabelHtml() . '</td>';
            $html .= '<td class="value">';

            $html .= '<select id="' . $regionIdHtmlId . '" name="' . $regionIdHtmlName . '" '
                 . $element->serialize($htmlAttributes) . '>' . "\n";
            foreach ($regionCollection as $region) {
                $selected = ($regionId == $region['value']) ? ' selected="selected"' : '';
                $value =  is_numeric($region['value']) ? (int) $region['value'] : '';
                $html .= '<option value="' . $value . '"' . $selected . '>'
                    . Mage::helper('adminhtml')->escapeHtml(Mage::helper('directory')->__($region['label']))
                    . '</option>';
            }
            $html .= '</select>' . "\n";

            $html .= '<input type="hidden" name="' . $regionHtmlName . '" id="' . $regionHtmlId . '" value=""/>';

            $html .= '</td>';
            $element->setClass($elementClass);
        } else {
            $element->setClass('input-text');
            $html .= '<td class="label"><label for="' . $element->getHtmlId() . '">'
                . $element->getLabel()
                . ' <span class="required" style="display:none">*</span></label></td>';

            $element->setRequired(false);
            $html .= '<td class="value">';
            $html .= '<input id="' . $regionHtmlId . '" name="' . $regionHtmlName
                . '" value="' . $element->getEscapedValue() . '" '
                . $element->serialize($htmlAttributes) . '/>' . "\n";
            $html .= '<input type="hidden" name="' . $regionIdHtmlName . '" id="' . $regionIdHtmlId . '" value=""/>';
            $html .= '</td>' . "\n";
        }
        return $html . ('</tr>' . "\n");
    }
}
