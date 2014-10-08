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
}