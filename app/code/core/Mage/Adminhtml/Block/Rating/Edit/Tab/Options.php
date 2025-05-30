<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Rating_Edit_Tab_Options extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('options_form', ['legend' => Mage::helper('rating')->__('Assigned Options')]);

        if (Mage::registry('rating_data')) {
            $collection = Mage::getModel('rating/rating_option')
                ->getResourceCollection()
                ->addRatingFilter(Mage::registry('rating_data')->getId())
                ->load();

            $i = 1;
            foreach ($collection->getItems() as $item) {
                $fieldset->addField(
                    'option_code_' . $item->getId(),
                    'text',
                    [
                        'label'     => Mage::helper('rating')->__('Option Label'),
                        'required'  => true,
                        'name'      => 'option_title[' . $item->getId() . ']',
                        'value'     => ($item->getCode()) ? $item->getCode() : $i,
                    ],
                );
                $i++;
            }
        } else {
            for ($i = 1; $i <= 5; $i++) {
                $fieldset->addField(
                    'option_code_' . $i,
                    'text',
                    [
                        'label'     => Mage::helper('rating')->__('Option Title'),
                        'required'  => true,
                        'name'      => 'option_title[add_' . $i . ']',
                        'value'     => $i,
                    ],
                );
            }
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
