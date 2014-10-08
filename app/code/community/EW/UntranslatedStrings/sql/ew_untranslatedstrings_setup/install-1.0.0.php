<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$tableName = $installer->getTable('ew_untranslatedstrings/string');

$table = new Varien_Db_Ddl_Table();
$table->setName($tableName);

$table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array('nullable' => false, 'identity' => true, 'primary' => true));
$table->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array('nullable' => false));
$table->addColumn('untranslated_string', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false));
$table->addColumn('translation_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false));
$table->addColumn('translation_module', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true));
$table->addColumn('locale', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array('nullable' => true));
$table->addColumn('url_found', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true));
$table->addColumn('date_found', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false));

$installer->getConnection()->createTable($table);

$uniqueFields = array(
    'store_id',
    'untranslated_string',
    'translation_code',
    'locale'
);
$installer->getConnection()->addIndex(
    $tableName,
    $installer->getIdxName(
        $tableName,
        $uniqueFields,
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    $uniqueFields,
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();