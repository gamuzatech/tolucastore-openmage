<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

function updatePicPayTransactionsTable ($installer, $model, $description)
{
    $table = $installer->getTable ($model);

    $installer->getConnection ()
        ->addColumn ($table, 'qrcode_content', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'comment'  => 'QRCode Content',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'qrcode_base64', array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'comment'  => 'QRCode Base64',
        ));
}

updatePicPayTransactionsTable ($installer, Gamuza_PicPay_Helper_Data::TRANSACTION_TABLE, 'Gamuza PicPay Transaction');

$installer->endSetup ();

