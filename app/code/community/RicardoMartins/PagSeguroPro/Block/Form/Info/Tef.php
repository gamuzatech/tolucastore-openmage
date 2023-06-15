<?php
class RicardoMartins_PagSeguroPro_Block_Form_Info_Tef extends Mage_Payment_Block_Info
{
    /**
     * Set block template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ricardomartins_pagseguropro/form/info/tef.phtml');
    }
}