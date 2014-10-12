<?php

class EW_UntranslatedStrings_Model_Resource_String extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct() {
        $this->_init('ew_untranslatedstrings/string', 'id');
    }

    /**
     * Write set of untranslated strings. Expects array of format
     * array(
     *     array(
     *         'code' => code,
     *         'module' => module,
     *         'text' => untranslated string,
     *         'store_id' => store ID,
     *         'locale' => locale,
     *         'url' => url found
     *     ),
     *     ...
     * )
     *
     * @param array $strings
     */
    public function writeUntranslatedStrings(array $strings) {
        $write = $this->_getWriteAdapter();

        // map expected keys to database columns
        $columnMapping = array(
            'code' => 'translation_code',
            'module' => 'translation_module',
            'text' => 'untranslated_string',
            'store_id' => 'store_id',
            'locale' => 'locale',
            'url' => 'url_found'
        );

        $insertValues = array();

        foreach($strings as $string) {
            $insertValue = array(
                'date_found' => Zend_Date::now()->toString(Zend_Date::ISO_8601)
            );

            foreach($string as $key => $value) {
                $insertValue[ $columnMapping[$key] ] = $value;
            }

            $insertValues[] = $insertValue;
        }

        $write->insertOnDuplicate(
            $this->getMainTable(),
            $insertValues,
            array(
                'encounter_count' => new Zend_Db_Expr(
                    sprintf(
                        '%s + 1',
                        $write->quoteIdentifier('encounter_count')
                    )
                )
            )
        );
    }

    public function getLocaleStrings($locale) {
        $read = $this->getReadConnection();

        $select = $read->select();

        $select->from(array('main_table' => $this->getMainTable()));
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns(
            array(
                'id',
                'untranslated_string',
                'translation_code',
                'translation_module'
            )
        );
        $select->where('locale = ?', $locale);
        $select->distinct(true);

        $rawResults = $select->query()->fetchAll();

        return $rawResults;
    }

    public function purgeStrings(array $ids) {
        $where = $this->_getWriteAdapter()->quoteInto('id in (?)', $ids);
        $this->_getWriteAdapter()->delete($this->getMainTable(), $where);
    }
}