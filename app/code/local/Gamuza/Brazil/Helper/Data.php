<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Helper_Data extends Mage_Core_Helper_Abstract
{
    const PRODUCT_ATTRIBUTE_BRAZIL_NCM  = 'brazil_ncm';
    const PRODUCT_ATTRIBUTE_BRAZIL_CEST = 'brazil_cest';
    const PRODUCT_ATTRIBUTE_BRAZIL_CFOP = 'brazil_cfop';

    const CUSTOMER_ATTRIBUTE_RG_IE = 'br_rg_ie';

    const ORDER_ATTRIBUTE_IS_PIX = 'is_pix';

    const NFE_VERSION_3_10 = '3.10';
    const NFE_VERSION_4_00 = '4.00';

    const NFE_ENVIRONMENT_PRODUCTION   = 1;
    const NFE_ENVIRONMENT_HOMOLOGATION = 2;
}

