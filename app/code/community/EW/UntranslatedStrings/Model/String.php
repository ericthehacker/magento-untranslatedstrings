<?php

class EW_UntranslatedStrings_Model_String extends Mage_Core_Model_Abstract
{
    protected function _construct() {
        $this->_init('ew_untranslatedstrings/string');
    }

    /**
     * Purge strings which ARE translated
     *
     * @param $locale
     */
    public function purgeTranslatedRecords($locale) {
        $strings = $this->getResource()->getLocaleStrings($locale);

        /* @var $translate EW_UntranslatedStrings_Model_Core_Translate */
        $translate = Mage::helper('ew_untranslatedstrings')->getTranslator($locale);

        $purgeIds = array();
        foreach($strings as $string) {
            $id = $string['id'];
            $text = $string['untranslated_string'];
            $code = $string['translation_code'];
            $module = $string['translation_module'];

            if($translate->hasTranslation($text,$code)) {
                $purgeIds[] = $id;
            }
        }

        if(empty($purgeIds)) {
            return;
        }

        $this->getResource()->purgeStrings($purgeIds);
    }
}