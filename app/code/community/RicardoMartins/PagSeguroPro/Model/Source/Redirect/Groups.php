<?php
/**
 * PagSeguro Transparente Magento
 *
 * @package     RicardoMartins_PagSeguro
 * @copyright   Copyright (c) 2019
 * @author      Ricardo Martins <pagseguro-transparente@ricardomartins.net.br>
 * @license     https://opensource.org/licenses/GPL-2.0
 */
class RicardoMartins_PagSeguroPro_Model_Source_Redirect_Groups
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            array('value' => 'CREDIT_CARD',    'label' => 'Cartões de Crédito'),
            array('value' => 'BOLETO', 'label' => 'Boleto'),
            array('value' => 'PIX', 'label' => 'Pix')
        );

        return $options;
    }
}

