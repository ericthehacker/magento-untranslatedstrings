<?php

class EW_UntranslatedStrings_Model_Resource_String_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    private $_joinedStoreCode = false;

    protected function _construct() {
        $this->_init('ew_untranslatedstrings/string');
    }

    /**
     * Join store code
     */
    public function joinStoreCode() {
        if(!$this->_joinedStoreCode) {
            $this->join(
                array('stores' => 'core/store'),
                '`stores`.`store_id` = `main_table`.`store_id`',
                array('store_code' => 'stores.code')
            );
        }
        $this->_joinedStoreCode = true;
    }

    /**
     * Configures collection to be summary of
     * untranslated strings by locale.
     */
    public function configureSummary($topStringsCount = 10) {
        $this->getSelect()
             ->reset(Zend_Db_Select::COLUMNS)
             ->columns(
                 array(
                     'string_count' => new Zend_Db_Expr(
                         sprintf(
                             'sum(%s)',
                             $this->getConnection()->quoteIdentifier('encounter_count')
                         )
                     ),
                     'locale',
                     'top_strings' => new Zend_Db_Expr(
                         sprintf(
                             'SUBSTRING_INDEX(GROUP_CONCAT(%s ORDER BY %s SEPARATOR \'\n\'), \'\n\', %d)',
                             $this->getConnection()->quoteIdentifier('untranslated_string'),
                             $this->getConnection()->quoteIdentifier('encounter_count'),
                             $topStringsCount
                         )
                     )
                 )
             );

        $this->getSelect()
             ->distinct(true)
             ->group('locale');
    }
}