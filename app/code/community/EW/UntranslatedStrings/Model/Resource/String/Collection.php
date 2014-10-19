<?php

class EW_UntranslatedStrings_Model_Resource_String_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    private $_joinedStoreCode = false;
    private $_interferWithCountSql = false;

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
     * Account for group and having clauses, if any
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        if(!$this->_interferWithCountSql) {
            return parent::getSelectCountSql();
        }

        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        //$countSelect->reset(Zend_Db_Select::COLUMNS);

        //$countSelect->columns('COUNT(*)');

        return $countSelect;
    }

    /**
     * Get collection size
     *
     * @return int
     */
    public function getSize()
    {
        if(!$this->_interferWithCountSql) {
            return parent::getSize();
        }

        if (is_null($this->_totalRecords)) {
            $sql = $this->getSelectCountSql();
            $this->_totalRecords = count($this->getConnection()->fetchAll($sql, $this->_bindParams));
        }
        return intval($this->_totalRecords);
    }

    /**
     * Account for group and having clauses, if any
     *
     * @return array
     */
    public function getAllIds()
    {
        if(!$this->_interferWithCountSql) {
            return parent::getAllIds();
        }

        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        //$idsSelect->reset(Zend_Db_Select::COLUMNS);

        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');
        return $this->getConnection()->fetchCol($idsSelect);
    }

    /**
     * Configures collection to be summary of
     * untranslated strings by locale.
     */
    public function configureSummary($topStringsCount = 10) {
        $this->_interferWithCountSql = true;

        $this->getSelect()
             ->reset(Zend_Db_Select::COLUMNS)
             ->columns(
                 array(
                     'string_count' => new Zend_Db_Expr(
                         sprintf(
                             'count(%s)',
                             $this->getConnection()->quoteIdentifier('untranslated_string')
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
             ->group('locale')
             ->having('string_count > 0');
    }
}