<?php

class EW_UntranslatedStrings_Model_Resource_String_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct() {
        $this->_init('ew_untranslatedstrings/string');
    }
}