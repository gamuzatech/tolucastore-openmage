<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Helper_Data extends Mage_Core_Helper_Abstract
{
    const NFCE_TABLE = 'gamuza_brazil_nfce';

    const PRODUCT_ATTRIBUTE_BRAZIL_NCM  = 'brazil_ncm';
    const PRODUCT_ATTRIBUTE_BRAZIL_CEST = 'brazil_cest';
    const PRODUCT_ATTRIBUTE_BRAZIL_CFOP = 'brazil_cfop';

    const CUSTOMER_ATTRIBUTE_RG_IE = 'br_rg_ie';

    const ORDER_ATTRIBUTE_IS_PIX = 'is_pix';

    const NFE_VERSION_3_10 = '3.10';
    const NFE_VERSION_4_00 = '4.00';

    const NFE_CONSUMER_NORMAL = 0;
    const NFE_CONSUMER_FINAL  = 1;

    const NFE_CRT_SIMPLE_NATIONAL        = 1;
    const NFE_CRT_SIMPLE_NATIONAL_EXCESS = 2;
    const NFE_CRT_NORMAL_REGIME          = 3;

    const NFE_CUSTOMER_IE_ICMS = 1;
    const NFE_CUSTOMER_IE_FREE = 2;
    const NFE_CUSTOMER_IE_NONE = 3;

    const NFE_DESTINY_INTERNAL   = 1;
    const NFE_DESTINY_INTERSTATE = 2;
    const NFE_DESTINY_ABROAD     = 3;

    const NFE_EMISSION_NORMAL             = 1;
    const NFE_EMISSION_CONTINGENCY_FS     = 2;
    const NFE_EMISSION_CONTINGENCY_SCAN   = 3;
    const NFE_EMISSION_CONTINGENCY_DPEC   = 4;
    const NFE_EMISSION_CONTINGENCY_FS_DA  = 5;
    const NFE_EMISSION_CONTINGENCY_SVC_AN = 6;
    const NFE_EMISSION_CONTINGENCY_SVC_RS = 7;

    const NFE_ENVIRONMENT_PRODUCTION   = 1;
    const NFE_ENVIRONMENT_HOMOLOGATION = 2;

    const NFE_FINALITY_NORMAL        = 1;
    const NFE_FINALITY_COMPLEMENTARY = 2;
    const NFE_FINALITY_ADJUSTMENT    = 3;

    const NFE_FREIGHT_EMITTER  = 0;
    const NFE_FREIGHT_RECEIVER = 1;
    const NFE_FREIGHT_THIRD    = 2;
    const NFE_FREIGHT_NONE     = 9;

    const NFE_INTERMEDIARY_OWN   = 0;
    const NFE_INTERMEDIARY_THIRD = 1;

    const NFE_OPERATION_INPUT  = 0;
    const NFE_OPERATION_OUTPUT = 1;

    const NFE_PAYMENT_TYPE_MONEY        = '01';
    const NFE_PAYMENT_TYPE_CHECK        = '02';
    const NFE_PAYMENT_TYPE_CREDIT_CARD  = '03';
    const NFE_PAYMENT_TYPE_DEBIT_CARD   = '04';
    const NFE_PAYMENT_TYPE_STORE_CREDIT = '05';
    const NFE_PAYMENT_TYPE_FOOD_VOUCHER = '10';
    const NFE_PAYMENT_TYPE_MEAL_TICKET  = '11';
    const NFE_PAYMENT_TYPE_GIFT_VOUCHER = '12';
    const NFE_PAYMENT_TYPE_FUEL_VOUCHER = '13';
    const NFE_PAYMENT_TYPE_BANK_SLIP    = '15';
    const NFE_PAYMENT_TYPE_NONE         = '90';
    const NFE_PAYMENT_TYPE_OTHER        = '99';

    const NFE_PRESENCE_NONE        = 0;
    const NFE_PRESENCE_LOCAL       = 1;
    const NFE_PRESENCE_INTERNET    = 2;
    const NFE_PRESENCE_TELESERVICE = 3;
    const NFE_PRESENCE_DELIVERY    = 4;
    const NFE_PRESENCE_EXTERNAL    = 5;
    const NFE_PRESENCE_OTHER       = 9;

    const NFE_PRINT_PORTRAIT  = 1;
    const NFE_PRINT_LANDSCAPE = 2;

    const NFE_PROCESS_PDV        = 0;
    const NFE_PROCESS_FISCO      = 1;
    const NFE_PROCESS_FISCO_SITE = 2;
    const NFE_PROCESS_FISCO_PDV  = 3;

    const XML_PATH_BRAZIL_NFCE_ENVIRONMENT = 'brazil/nfce/environment';
    const XML_PATH_BRAZIL_NFCE_VERSION     = 'brazil/nfce/version';

    const XML_PATH_BRAZIL_NFCE_MODEL  = 'brazil/nfce/model';
    const XML_PATH_BRAZIL_NFCE_SERIES = 'brazil/nfce/series';

    const XML_PATH_BRAZIL_NFCE_BATCH_ID  = 'brazil/nfce/batch_id';
    const XML_PATH_BRAZIL_NFCE_REGION_ID = 'brazil/nfce/region_id';
    const XML_PATH_BRAZIL_NFCE_CITY_ID   = 'brazil/nfce/city_id';
    const XML_PATH_BRAZIL_NFCE_NUMBER_ID = 'brazil/nfce/number_id';

    public function getNumberId ($type)
    {
        $filename = sprintf (
            '%s%s%s_get_number_id.lock',
            Mage::app ()->getConfig ()->getVarDir ('locks'),
            DS, $type
        );

        $fp = fopen ($filename, 'a');

        fwrite ($fp, date ('c') . PHP_EOL);
        flock  ($fp, LOCK_EX);

        $path = sprintf ("brazil/{$type}/number_id");

        $result = Mage::getStoreConfig ($path);

        Mage::getModel ('core/config')->saveConfig ($path, $result + 1);

        flock  ($fp, LOCK_UN);
        fclose ($fp);

        return $result;
    }
}

