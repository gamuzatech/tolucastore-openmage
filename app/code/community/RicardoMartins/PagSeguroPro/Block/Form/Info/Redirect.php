<?php
class RicardoMartins_PagSeguroPro_Block_Form_Info_Redirect extends Mage_Payment_Block_Info
{
    private $_redirectUrl;

    /**
     * Set block template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ricardomartins_pagseguropro/form/info/redirect.phtml');
    }

    public function getPaymentLink()
    {
        if (is_null($this->_redirectUrl)) {
            $this->_convertAdditionalData();
        }
        return $this->_redirectUrl;
    }

    protected function _convertAdditionalData()
    {
        $details = false;
        try {
            $details = Mage::helper('core/unserializeArray')
                ->unserialize($this->getInfo()->getAdditionalData());
        } catch (Exception $e) {
            Mage::logException($e);
        }
        if (is_array($details)) {
            $this->_redirectUrl = isset($details['redirect_url']) ? (string) $details['redirect_url'] : '';
        } else {
            $this->_redirectUrl = '';
        }
        return $this;
    }
}