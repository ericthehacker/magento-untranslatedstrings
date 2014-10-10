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
                "`stores`.`store_id` = `main_table`.`store_id`",
                array('store_code' => 'stores.code')
            );
        }
        $this->_joinedStoreCode = true;
    }
}