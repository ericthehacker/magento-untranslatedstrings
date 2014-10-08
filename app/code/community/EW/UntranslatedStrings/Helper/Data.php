<?php

class EW_UntranslatedStrings_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_ENABLED = 'dev/translate/untranslated_strings_enabled';
    const CONFIG_PATH_BATCH_LOCALES_ENABLED = 'dev/translate/untranslated_strings_batch_locales_enabled';
    const CONFIG_PATH_BATCH_LOCALES = 'dev/translate/untranslated_strings_locales';

    /** @var array */
    private $_translators = null;

    /**
     * Is functionality enabled?
     *
     * @return bool
     */
    public function isEnabled() {
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_ENABLED);
    }

    /**
     * Get translator prepared for given locale
     *
     * @param $locale
     * @return EW_UntranslatedStrings_Model_Core_Translate
     */
    public function getTranslator($locale) {
        if(!isset($this->_translators[$locale])) {
            /* @var $translate EW_UntranslatedStrings_Model_Core_Translate */
            $translate = Mage::getModel('ew_untranslatedstrings/core_translate');
            $translate->setConfig(
                array(
                    Mage_Core_Model_Translate::CONFIG_KEY_LOCALE => $locale
                )
            );
            $translate->setLocale($locale);
            $translate->init('frontend'); //@todo: const


            $this->_translators[$locale] = $translate;
        }

        return $this->_translators[$locale];
    }

    /**
     * Does text/code have translation for given locale?
     *
     * @param $text
     * @param $code
     * @param $locale
     * @return bool
     */
    public function isTranslated($text, $code, $locale) {
        /* @var $translate EW_UntranslatedStrings_Model_Core_Translate */
        $translate = $this->getTranslator($locale);

        return $translate->hasTranslation($text, $code);
    }

    /**
     * Should check batch locales?
     *
     * @return bool
     */
    public function getCheckBatchLocales() {
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_BATCH_LOCALES_ENABLED);
    }

    /**
     * Return array of locale codes to check
     * @return array
     */
    public function getCheckLocales() {
        if($this->getCheckBatchLocales()) {
            return explode(',', Mage::getStoreConfig(self::CONFIG_PATH_BATCH_LOCALES));
        }

        //not enabled, so stick to configured locale
        return array(Mage::app()->getLocale()->getLocaleCode());
    }
}