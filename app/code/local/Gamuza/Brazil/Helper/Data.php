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

    const NFE_CERTIFICATE_A1_REPOSITORY = 0;
    const NFE_CERTIFICATE_A1_FILE = 1;
    const NFE_CERTIFICATE_A3 = 2;
    const NFE_CERTIFICATE_A1_BYTE_ARRAY = 3;

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

    const XML_PATH_BRAZIL_CERTIFICATE_TYPE     = 'brazil/certificate/type';
    const XML_PATH_BRAZIL_CERTIFICATE_FILENAME = 'brazil/certificate/filename';
    const XML_PATH_BRAZIL_CERTIFICATE_PASSWORD = 'brazil/certificate/password';

    const XML_PATH_BRAZIL_CSC_ID   = 'brazil/csc/id';
    const XML_PATH_BRAZIL_CSC_CODE = 'brazil/csc/code';

    const XML_PATH_BRAZIL_SETTING_ENVIRONMENT_ID = 'brazil/setting/environment_id';
    const XML_PATH_BRAZIL_SETTING_VERSION_ID     = 'brazil/setting/version_id';
    const XML_PATH_BRAZIL_SETTING_COUNTRY_ID     = 'brazil/setting/country_id';
    const XML_PATH_BRAZIL_SETTING_REGION_ID      = 'brazil/setting/region_id';
    const XML_PATH_BRAZIL_SETTING_CITY_ID        = 'brazil/setting/city_id';

    const XML_PATH_BRAZIL_NFE_BATCH_ID  = 'brazil/nfe/batch_id';
    const XML_PATH_BRAZIL_NFE_NUMBER_ID = 'brazil/nfe/number_id';
    const XML_PATH_BRAZIL_NFE_MODEL_ID  = 'brazil/nfe/model_id';
    const XML_PATH_BRAZIL_NFE_SERIES_ID = 'brazil/nfe/series_id';

    const XML_PATH_BRAZIL_NFCE_BATCH_ID  = 'brazil/nfce/batch_id';
    const XML_PATH_BRAZIL_NFCE_NUMBER_ID = 'brazil/nfce/number_id';
    const XML_PATH_BRAZIL_NFCE_MODEL_ID  = 'brazil/nfce/model_id';
    const XML_PATH_BRAZIL_NFCE_SERIES_ID = 'brazil/nfce/series_id';

    public function getNumberId ($type, $contents = null)
    {
        $filename = sprintf (
            '%s%s%s_get_number_id.lock',
            Mage::app ()->getConfig ()->getVarDir ('locks'),
            DS, $type
        );

        $fp = fopen ($filename, 'a');

        flock  ($fp, LOCK_EX);
        fwrite ($fp, sprintf ("%s: %s\n", date ('c'), json_encode ($contents)));

        $path = sprintf ("brazil/{$type}/number_id");

        $result = Mage::getStoreConfig ($path);

        Mage::getModel ('core/config')->saveConfig ($path, $result + 1);

        flock  ($fp, LOCK_UN);
        fclose ($fp);

        return $result;
    }
}

