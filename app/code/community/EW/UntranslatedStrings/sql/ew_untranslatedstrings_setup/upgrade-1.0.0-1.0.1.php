<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()
          ->addColumn(
              $installer->getTable('ew_untranslatedstrings/string'),
              'encounter_count',
              array(
                  'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                  'length'    => 11,
                  'nullable'  => false,
                  'default'   => 0,
                  'comment'   => 'Number of times string encountered'
              )
          );

$installer->endSetup();