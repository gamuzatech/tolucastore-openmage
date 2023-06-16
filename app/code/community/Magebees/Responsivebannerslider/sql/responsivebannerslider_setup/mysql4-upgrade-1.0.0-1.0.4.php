<?php
$installer = $this;
$installer->startSetup();
if(in_array($this->getTable('permission_block'), $installer->getConnection()->listTables())){
$installer->run(
    "
    INSERT INTO {$this->getTable('permission_block')} (block_name,is_allowed) values ('responsivebannerslider/view','1');
    
"
);
}

$installer->endSetup(); 
