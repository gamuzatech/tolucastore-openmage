<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Helper_Data extends Mage_Core_Helper_Abstract
{
    const IBPT_TABLE = 'gamuza_brazil_ibpt';
    const CEST_TABLE = 'gamuza_brazil_cest';
    const CFOP_TABLE = 'gamuza_brazil_cfop';
    const COUNTRY_TABLE = 'gamuza_brazil_country';
    const REGION_TABLE  = 'gamuza_brazil_region';
    const CITY_TABLE    = 'gamuza_brazil_city';
    const NFCE_TABLE          = 'gamuza_brazil_nfce';
    const NFCE_RESPONSE_TABLE = 'gamuza_brazil_nfce_response';
    const NFCE_EVENT_TABLE    = 'gamuza_brazil_nfce_event';

    const PRODUCT_ATTRIBUTE_BRAZIL_NCM  = 'brazil_ncm';
    const PRODUCT_ATTRIBUTE_BRAZIL_CEST = 'brazil_cest';
    const PRODUCT_ATTRIBUTE_BRAZIL_CFOP = 'brazil_cfop';

    const PRODUCT_ATTRIBUTE_GTIN = 'gtin';

    const CUSTOMER_ATTRIBUTE_BRAZIL_RG_IE   = 'brazil_rg_ie';
    const CUSTOMER_ATTRIBUTE_BRAZIL_IE_ICMS = 'brazil_ie_icms';

    const ORDER_ATTRIBUTE_BRAZIL_RG_IE   = self::CUSTOMER_ATTRIBUTE_BRAZIL_RG_IE;
    const ORDER_ATTRIBUTE_BRAZIL_IE_ICMS = self::CUSTOMER_ATTRIBUTE_BRAZIL_IE_ICMS;

    const ORDER_ATTRIBUTE_IS_PIX = 'is_pix';

    const IBPT_TYPE_NCM   = 0;
    const IBPT_TYPE_NBS   = 1;
    const IBPT_TYPE_LC116 = 2;

    const DFE_MODEL_NFE    = 55;
    const DFE_MODEL_CTE    = 57;
    const DFE_MODEL_MDFE   = 58;
    const DFE_MODEL_NFCE   = 65;
    const DFE_MODEL_CTE_OS = 67;

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
    const NFE_CRT_SIMPLE_NATIONAL_MEI    = 4;

    const NFE_CUSTOMER_IE_ICMS = 1;
    const NFE_CUSTOMER_IE_FREE = 2;
    const NFE_CUSTOMER_IE_NONE = 9;

    const NFE_DESTINY_INTERNAL   = 1;
    const NFE_DESTINY_INTERSTATE = 2;
    const NFE_DESTINY_ABROAD     = 3;

    const NFE_EMISSION_NORMAL              = 1;
    const NFE_EMISSION_CONTINGENCY_FS_IA   = 2;
    const NFE_EMISSION_CONTINGENCY_SCAN    = 3;
    const NFE_EMISSION_CONTINGENCY_DPEC    = 4;
    const NFE_EMISSION_CONTINGENCY_FS_DA   = 5;
    const NFE_EMISSION_CONTINGENCY_SVC_AN  = 6;
    const NFE_EMISSION_CONTINGENCY_SVC_RS  = 7;
    const NFE_EMISSION_CONTINGENCY_OFFLINE = 9;

    const NFE_ENVIRONMENT_PRODUCTION   = 1;
    const NFE_ENVIRONMENT_HOMOLOGATION = 2;

    const NFE_FINALITY_NORMAL        = 1;
    const NFE_FINALITY_COMPLEMENTARY = 2;
    const NFE_FINALITY_ADJUSTMENT    = 3;
    const NFE_FINALITY_DEVOLUTION    = 4;

    const NFE_FREIGHT_EMITTER_CIF  = 0;
    const NFE_FREIGHT_RECEIVER_FOB = 1;
    const NFE_FREIGHT_THIRD        = 2;
    const NFE_FREIGHT_OWN_EMITTER  = 3;
    const NFE_FREIGHT_OWN_RECEIVER = 4;
    const NFE_FREIGHT_NONE         = 9;

    const NFE_INTERMEDIARY_OWN   = 0;
    const NFE_INTERMEDIARY_THIRD = 1;

    const NFE_OPERATION_INPUT  = 0;
    const NFE_OPERATION_OUTPUT = 1;

    const NFE_PAYMENT_TYPE_MONEY         = 1;
    const NFE_PAYMENT_TYPE_CHECK         = 2;
    const NFE_PAYMENT_TYPE_CREDIT_CARD   = 3;
    const NFE_PAYMENT_TYPE_DEBIT_CARD    = 4;
    const NFE_PAYMENT_TYPE_STORE_CARD    = 5;
    const NFE_PAYMENT_TYPE_FOOD_VOUCHER  = 10;
    const NFE_PAYMENT_TYPE_MEAL_TICKET   = 11;
    const NFE_PAYMENT_TYPE_GIFT_VOUCHER  = 12;
    const NFE_PAYMENT_TYPE_FUEL_VOUCHER  = 13;
    const NFE_PAYMENT_TYPE_COMMERCIAL_DUPLICATE = 14;
    const NFE_PAYMENT_TYPE_BANK_SLIP     = 15;
    const NFE_PAYMENT_TYPE_BANK_DEPOSIT  = 16;
    const NFE_PAYMENT_TYPE_DYNAMIC_PIX   = 17;
    const NFE_PAYMENT_TYPE_BANK_TRANSFER = 18;
    const NFE_PAYMENT_TYPE_FIDELITY      = 19;
    const NFE_PAYMENT_TYPE_STATIC_PIX    = 20;
    const NFE_PAYMENT_TYPE_STORE_CREDIT  = 21;
    const NFE_PAYMENT_TYPE_NOT_INFORMED  = 22;
    const NFE_PAYMENT_TYPE_NONE          = 90;
    const NFE_PAYMENT_TYPE_OTHER         = 99;

    const NFE_PRESENCE_NONE        = 0;
    const NFE_PRESENCE_LOCAL       = 1;
    const NFE_PRESENCE_INTERNET    = 2;
    const NFE_PRESENCE_TELESERVICE = 3;
    const NFE_PRESENCE_DELIVERY    = 4;
    const NFE_PRESENCE_EXTERNAL    = 5;
    const NFE_PRESENCE_OTHER       = 9;

    const NFE_PRINT_NONE       = 0;
    const NFE_PRINT_PORTRAIT   = 1;
    const NFE_PRINT_LANDSCAPE  = 2;
    const NFE_PRINT_SIMPLIFIED = 3;
    const NFE_PRINT_NFCE       = 4;
    const NFE_PRINT_ELETRONIC_MESSAGE = 5;

    const NFE_PROCESS_PDV        = 0;
    const NFE_PROCESS_FISCO      = 1;
    const NFE_PROCESS_FISCO_SITE = 2;
    const NFE_PROCESS_FISCO_PDV  = 3;

    const NFE_STATUS_CREATED    = 'created';
    const NFE_STATUS_SIGNED     = 'signed';
    const NFE_STATUS_AUTHORIZED = 'authorized';
    const NFE_STATUS_DENIED     = 'denied';
    const NFE_STATUS_CANCELED   = 'canceled';

    const NFE_RESPONSE_AUTHORIZED = 100;
    const NFE_RESPONSE_DUPLICATED = 204;

    const NFE_EVENT_CANCELED = 135;

    const XML_PATH_BRAZIL_IBPT_IMPORT   = 'brazil/ibpt/import';
    const XML_PATH_BRAZIL_IBPT_KEY      = 'brazil/ibpt/key';
    const XML_PATH_BRAZIL_IBPT_SOURCE   = 'brazil/ibpt/source';
    const XML_PATH_BRAZIL_IBPT_VERSION  = 'brazil/ibpt/version';
    const XML_PATH_BRAZIL_IBPT_BEGIN_AT = 'brazil/ibpt/begin_at';
    const XML_PATH_BRAZIL_IBPT_END_AT   = 'brazil/ibpt/end_at';
    const XML_PATH_BRAZIL_IBPT_VALIDATE = 'brazil/ibpt/validate';

    const XML_PATH_BRAZIL_CEST_IMPORT   = 'brazil/cest/import';
    const XML_PATH_BRAZIL_CEST_VERSION  = 'brazil/cest/version';
    const XML_PATH_BRAZIL_CEST_BEGIN_AT = 'brazil/cest/begin_at';
    const XML_PATH_BRAZIL_CEST_END_AT   = 'brazil/cest/end_at';
    const XML_PATH_BRAZIL_CEST_VALIDATE = 'brazil/cest/validate';

    const XML_PATH_BRAZIL_CFOP_IMPORT   = 'brazil/cfop/import';
    const XML_PATH_BRAZIL_CFOP_VERSION  = 'brazil/cfop/version';
    const XML_PATH_BRAZIL_CFOP_BEGIN_AT = 'brazil/cfop/begin_at';
    const XML_PATH_BRAZIL_CFOP_END_AT   = 'brazil/cfop/end_at';
    const XML_PATH_BRAZIL_CFOP_VALIDATE = 'brazil/cfop/validate';

    const XML_PATH_BRAZIL_COUNTRY_IMPORT   = 'brazil/country/import';
    const XML_PATH_BRAZIL_COUNTRY_VERSION  = 'brazil/country/version';
    const XML_PATH_BRAZIL_COUNTRY_BEGIN_AT = 'brazil/country/begin_at';
    const XML_PATH_BRAZIL_COUNTRY_END_AT   = 'brazil/country/end_at';
    const XML_PATH_BRAZIL_COUNTRY_VALIDATE = 'brazil/country/validate';

    const XML_PATH_BRAZIL_REGION_IMPORT   = 'brazil/region/import';
    const XML_PATH_BRAZIL_REGION_VERSION  = 'brazil/region/version';
    const XML_PATH_BRAZIL_REGION_BEGIN_AT = 'brazil/region/begin_at';
    const XML_PATH_BRAZIL_REGION_END_AT   = 'brazil/region/end_at';
    const XML_PATH_BRAZIL_REGION_VALIDATE = 'brazil/region/validate';

    const XML_PATH_BRAZIL_CITY_IMPORT   = 'brazil/city/import';
    const XML_PATH_BRAZIL_CITY_VERSION  = 'brazil/city/version';
    const XML_PATH_BRAZIL_CITY_BEGIN_AT = 'brazil/city/begin_at';
    const XML_PATH_BRAZIL_CITY_END_AT   = 'brazil/city/end_at';
    const XML_PATH_BRAZIL_CITY_VALIDATE = 'brazil/city/validate';

    const XML_PATH_BRAZIL_CERTIFICATE_TYPE_ID  = 'brazil/certificate/type_id';
    const XML_PATH_BRAZIL_CERTIFICATE_FILENAME = 'brazil/certificate/filename';
    const XML_PATH_BRAZIL_CERTIFICATE_PASSWORD = 'brazil/certificate/password';

    const XML_PATH_BRAZIL_CSC_ID   = 'brazil/csc/id';
    const XML_PATH_BRAZIL_CSC_CODE = 'brazil/csc/code';

    const XML_PATH_BRAZIL_SETTING_ACTIVE         = 'brazil/setting/active';
    const XML_PATH_BRAZIL_SETTING_ENVIRONMENT_ID = 'brazil/setting/environment_id';
    const XML_PATH_BRAZIL_SETTING_VERSION        = 'brazil/setting/version';
    const XML_PATH_BRAZIL_SETTING_TIMEOUT        = 'brazil/setting/timeout';
    const XML_PATH_BRAZIL_SETTING_COUNTRY_ID     = 'brazil/setting/country_id';
    const XML_PATH_BRAZIL_SETTING_REGION_ID      = 'brazil/setting/region_id';
    const XML_PATH_BRAZIL_SETTING_CITY_ID        = 'brazil/setting/city_id';
    const XML_PATH_BRAZIL_SETTING_CRT_ID         = 'brazil/setting/crt_id';
    const XML_PATH_BRAZIL_SETTING_COMPANY_IE     = 'brazil/setting/company_ie';
    const XML_PATH_BRAZIL_SETTING_COMPANY_IM     = 'brazil/setting/company_im';
    const XML_PATH_BRAZIL_SETTING_REMOVE_ACCENTS = 'brazil/setting/remove_accents';

    const XML_PATH_BRAZIL_NFE_PRINT_ID  = 'brazil/nfe/print_id';
    const XML_PATH_BRAZIL_NFE_BATCH_ID  = 'brazil/nfe/batch_id';
    const XML_PATH_BRAZIL_NFE_NUMBER_ID = 'brazil/nfe/number_id';
    const XML_PATH_BRAZIL_NFE_MODEL_ID  = 'brazil/nfe/model_id';
    const XML_PATH_BRAZIL_NFE_SERIES_ID = 'brazil/nfe/series_id';
    const XML_PATH_BRAZIL_NFE_AUTHORIZE_VALIDATE = 'brazil/nfe/authorize_validate';
    const XML_PATH_BRAZIL_NFE_CANCEL_VALIDATE    = 'brazil/nfe/cancel_validate';

    const XML_PATH_BRAZIL_NFCE_PRINT_ID  = 'brazil/nfce/print_id';
    const XML_PATH_BRAZIL_NFCE_BATCH_ID  = 'brazil/nfce/batch_id';
    const XML_PATH_BRAZIL_NFCE_NUMBER_ID = 'brazil/nfce/number_id';
    const XML_PATH_BRAZIL_NFCE_MODEL_ID  = 'brazil/nfce/model_id';
    const XML_PATH_BRAZIL_NFCE_SERIES_ID = 'brazil/nfce/series_id';
    const XML_PATH_BRAZIL_NFCE_AUTHORIZE_VALIDATE = 'brazil/nfce/authorize_validate';
    const XML_PATH_BRAZIL_NFCE_CANCEL_VALIDATE    = 'brazil/nfce/cancel_validate';

    public function getIncrementId ($type, $field, $contents = null)
    {
        $filename = sprintf (
            '%s%s%s_get_%s.lock',
            Mage::app ()->getConfig ()->getVarDir ('locks'),
            DS, $type, $field
        );

        $fp = fopen ($filename, 'a');

        flock  ($fp, LOCK_EX);
        fwrite ($fp, sprintf ("%s: %s%s", date ('c'), json_encode ($contents), PHP_EOL));

        $path = sprintf ("brazil/{$type}/{$field}");
        $value = Mage::getStoreConfig ($path);
        $result = intval ($value) + 1;

        fwrite ($fp, sprintf ("%s: %s%s", date ('c'), json_encode (array ($field => $value)), PHP_EOL));

        Mage::getModel ('core/config')->saveConfig ($path, $result);
        Mage::app ()->getCacheInstance ()->cleanType ('config');
        Mage::dispatchEvent ('adminhtml_cache_refresh_type', array ('type' => 'config'));

        flock  ($fp, LOCK_UN);
        fclose ($fp);

        return $value;
    }
}

