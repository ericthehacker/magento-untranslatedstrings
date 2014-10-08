<?php

class EW_UntranslatedStrings_Model_Resource_String extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct() {
        $this->_init('ew_untranslatedstrings/string', 'id');
    }

    public function writeUntranslatedString($code, $module, $string, $storeId, $locale, $url) {
        $write = $this->_getWriteAdapter();

        $write->insertIgnore(
            $this->getMainTable(),
            array(
                'id' => null,
                'store_id' => $storeId,
                'untranslated_string' => $string,
                'translation_code' => $code,
                'translation_module' => $module,
                'locale' => $locale,
                'url_found' => $url,
                'date_found' => Zend_Date::now()->toString(Zend_Date::ISO_8601)
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