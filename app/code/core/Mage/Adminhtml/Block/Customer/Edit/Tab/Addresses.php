<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Customer addresses forms
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customer/tab/addresses.phtml');
    }

    /**
     * @return string
     */
    public function getRegionsUrl()
    {
        return $this->getUrl('*/json/countryRegion');
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setChild(
            'delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData([
                    'label'  => Mage::helper('customer')->__('Delete Address'),
                    'name'   => 'delete_address',
                    'element_name' => 'delete_address',
                    'disabled' => $this->isReadonly(),
                    'class'  => 'delete' . ($this->isReadonly() ? ' disabled' : ''),
                ]),
        );
        $this->setChild(
            'add_address_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData([
                    'label'  => Mage::helper('customer')->__('Add New Address'),
                    'id'     => 'add_address_button',
                    'name'   => 'add_address_button',
                    'element_name' => 'add_address_button',
                    'disabled' => $this->isReadonly(),
                    'class'  => 'add' . ($this->isReadonly() ? ' disabled' : ''),
                    'onclick' => 'customerAddresses.addNewAddress()',
                ]),
        );
        $this->setChild(
            'cancel_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData([
                    'label'  => Mage::helper('customer')->__('Cancel'),
                    'id'     => 'cancel_add_address' . $this->getTemplatePrefix(),
                    'name'   => 'cancel_address',
                    'element_name' => 'cancel_address',
                    'class'  => 'cancel delete-address' . ($this->isReadonly() ? ' disabled' : ''),
                    'disabled' => $this->isReadonly(),
                    'onclick' => 'customerAddresses.cancelAdd(this)',
                ]),
        );
        return parent::_prepareLayout();
    }

    /**
     * Check block is readonly.
     *
     * @return bool
     */
    public function isReadonly()
    {
        $customer = Mage::registry('current_customer');
        return $customer->isReadonly();
    }

    /**
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * Initialize form object
     *
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function initForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('address_fieldset', [
            'legend'    => Mage::helper('customer')->__("Edit Customer's Address")]);

        $customer = $this->getRegistryCurrentCustomer();
        $addressModel = Mage::getModel('customer/address');
        $addressModel->setCountryId(Mage::helper('core')->getDefaultCountry($customer->getStore()));
        /** @var Mage_Customer_Model_Form $addressForm */
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('adminhtml_customer_address')
            ->setEntity($addressModel)
            ->initDefaultValues();

        $attributes = $addressForm->getAttributes();
        if (isset($attributes['street'])) {
            Mage::helper('adminhtml/addresses')
                ->processStreetAttribute($attributes['street']);
        }
        foreach ($attributes as $attribute) {
            /** @var Mage_Eav_Model_Entity_Attribute $attribute */
            $attribute->setFrontendLabel(Mage::helper('customer')->__($attribute->getFrontend()->getLabel()));
            $attribute->setNote(Mage::helper('customer')->__($attribute->getNote()));
            $attribute->unsIsVisible();
        }
        $this->_setFieldset($attributes, $fieldset);

        $regionElement = $form->getElement('region');
        if ($regionElement) {
            $isRequired = Mage::helper('directory')->isRegionRequired($addressModel->getCountryId());
            $regionElement->setRequired($isRequired);
            $regionElement->setRenderer(Mage::getModel('adminhtml/customer_renderer_region'));
        }

        $regionElement = $form->getElement('region_id');
        if ($regionElement) {
            $regionElement->setNoDisplay(true);
        }

        $country = $form->getElement('country_id');
        if ($country) {
            $country->addClass('countries');
        }

        if ($this->isReadonly()) {
            foreach ($addressModel->getAttributes() as $attribute) {
                $element = $form->getElement($attribute->getAttributeCode());
                if ($element) {
                    $element->setReadonly(true, true);
                }
            }
        }

        $customerStoreId = null;
        if ($customer->getId()) {
            $customerStoreId = Mage::app()->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
        }

        $prefixElement = $form->getElement('prefix');
        if ($prefixElement) {
            /** @var Mage_Customer_Helper_Data $helper */
            $helper = $this->helper('customer');
            $prefixOptions = $helper->getNamePrefixOptions($customerStoreId);
            if (!empty($prefixOptions)) {
                $fieldset->removeField($prefixElement->getId());
                $prefixField = $fieldset->addField(
                    $prefixElement->getId(),
                    'select',
                    $prefixElement->getData(),
                    '^',
                );
                $prefixField->setValues($prefixOptions);
            }
        }

        $suffixElement = $form->getElement('suffix');
        if ($suffixElement) {
            /** @var Mage_Customer_Helper_Data $helper */
            $helper = $this->helper('customer');
            $suffixOptions = $helper->getNameSuffixOptions($customerStoreId);
            if (!empty($suffixOptions)) {
                $fieldset->removeField($suffixElement->getId());
                $suffixField = $fieldset->addField(
                    $suffixElement->getId(),
                    'select',
                    $suffixElement->getData(),
                    $form->getElement('lastname')->getId(),
                );
                $suffixField->setValues($suffixOptions);
            }
        }

        $addressCollection = $customer->getAddresses();
        $this->assign('customer', $customer);
        $this->assign('addressCollection', $addressCollection);
        $form->setValues($addressModel->getData());
        $this->setForm($form);

        return $this;
    }

    /**
     * @return string
     */
    public function getCancelButtonHtml()
    {
        return $this->getChildHtml('cancel_button');
    }

    /**
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_address_button');
    }

    /**
     * @return string
     */
    public function getTemplatePrefix()
    {
        return '_template_';
    }

    /**
     * Return predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return [
            'file'      => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_file'),
            'image'     => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_image'),
            'boolean'   => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_boolean'),
        ];
    }

    /**
     * Return JSON object with countries associated to possible websites
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function getDefaultCountriesJson()
    {
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false, true);
        $result = [];
        foreach ($websites as $website) {
            $result[$website['value']] = Mage::app()->getWebsite($website['value'])->getConfig(
                Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY,
            );
        }

        return Mage::helper('core')->jsonEncode($result);
    }

    /**
     * Add specified values to name prefix element values
     *
     * @param string|int|array $values
     * @return $this
     */
    public function addValuesToNamePrefixElement($values)
    {
        if ($this->getForm() && $this->getForm()->getElement('prefix')) {
            $this->getForm()->getElement('prefix')->addElementValues($values);
        }
        return $this;
    }

    /**
     * Add specified values to name suffix element values
     *
     * @param string|int|array $values
     * @return $this
     */
    public function addValuesToNameSuffixElement($values)
    {
        if ($this->getForm() && $this->getForm()->getElement('suffix')) {
            $this->getForm()->getElement('suffix')->addElementValues($values);
        }
        return $this;
    }

    protected function getRegistryCurrentCustomer(): ?Mage_Customer_Model_Customer
    {
        return Mage::registry('current_customer');
    }
}
