<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));

require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';

if (!Mage::isInstalled())
{
    echo "Application is not installed yet, please complete install wizard first.";

    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

try
{
    Mage::app('admin', 'store', $mageRunOptions)->setUseSessionInUrl(false);
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit;
}

umask(0);

try
{
    /**
     * Customer
     */
    $collection = Mage::getModel ('customer/customer')->getCollection ()
        ->addAttributeToFilter ('default_billing', array ('gt' => 0))
    ;

    foreach ($collection as $customer)
    {
        foreach ($customer->getAddresses () as $address)
        {
            if ($address->getId () == $customer->getDefaultBilling ())
            {
                $customer->setCellphone ($address->getCellphone ())->save ();
            }
        }
    }

    /**
     * Quote
     */
    $customerEntityTypeId = Mage::getModel ('eav/entity')->setType ('customer')->getTypeId ();

    $customerCellphoneAttribute = Mage::getModel ('eav/entity_attribute')->loadByCode (
        $customerEntityTypeId, 'cellphone'
    );

    $collection = Mage::getModel ('sales/quote')->getCollection ()
        ->addFieldToFilter (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV, array ('eq' => true))
        ->addFieldToFilter (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID, array ('gt' => 0))
        ->addFieldToSelect ('entity_id')
    ;

    $collection->getSelect ()
        ->join(
            array ('cellphone' => Mage::getSingleton ('core/resource')->getTablename ('customer_entity_' . $customerCellphoneAttribute->getBackendType ())),
            sprintf ('main_table.pdv_customer_id = cellphone.entity_id AND cellphone.attribute_id = %s', $customerCellphoneAttribute->getAttributeId ()),
            array ('cellphone_value' => 'cellphone.value')
        )
        ->where ('cellphone.value IS NOT NULL')
    ;

    foreach ($collection as $quote)
    {
        $cellphone = $quote->getCellphoneValue ();

        $quote->unsCellphoneValue()
            ->setCustomerCellphone ($cellphone)
            ->getResource ()
            ->save ($quote)
        ;
    }
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;

    exit(1);
}

