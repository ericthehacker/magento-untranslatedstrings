<?php

class EW_UntranslatedStrings_Model_Core_Translate extends Mage_Core_Model_Translate
{
    const REGISTRY_KEY = 'ew_untranslatedstrings_string_buffer';

    /** @var array */
    protected $_localesToCheck = null;

    protected $_allowMatchingKeyValuePairs = false;
    protected $_allowLooseDevModuleMode = false;

    /**
     * Allow matching key / value translation pairs
     * when loading translations?
     *
     * @return bool
     */
    public function getAllowMatchingKeyValuePairs() {
        return $this->_allowMatchingKeyValuePairs;
    }

    /**
     * Set if matching key / value translation pairs
     * allowed when loading translations.
     *
     * @param bool $allow
     */
    public function setAllowMatchingKeyValuePairs($allow) {
        $this->_allowMatchingKeyValuePairs = (bool)$allow;
    }

    /**
     * Use native "Not allow use translation not related to module"
     * check when loading translations?
     *
     * @return bool
     */
    public function getAllowLooseDevModuleMode() {
        return $this->_allowLooseDevModuleMode;
    }

    /**
     * Set if native "Not allow use translation not related to module"
     * behavior used when loading translations.
     *
     * @param bool $allow
     */
    public function setAllowLooseDevModuleMode($allow) {
        $this->_allowLooseDevModuleMode = (bool)$allow;
    }

    /**
     * Get locales to check and store on local variable
     *
     * @return array
     */
    protected function _getLocalesToCheck() {
        if(is_null($this->_localesToCheck)) {
            $this->_localesToCheck = Mage::helper('ew_untranslatedstrings')->getCheckLocales();
        }

        return $this->_localesToCheck;
    }

    public function hasTranslation($text, $code) {
        if (array_key_exists($code, $this->getData()) || array_key_exists($text, $this->getData())) {
            return true;
        }

        return false;
    }

    protected function _checkTranslatedString($text, $code) {
        Varien_Profiler::start(__CLASS__ . '::' . __FUNCTION__);
        Varien_Profiler::start(EW_UntranslatedStrings_Helper_Data::PROFILER_KEY);

        //loop locale(s) and find gaps
        $untranslatedPhrases = array();
        foreach($this->_getLocalesToCheck() as $locale) {
            if(!Mage::helper('ew_untranslatedstrings')->isTranslated($text,$code,$locale)) {
                $untranslatedPhrases[] = array(
                    'text' => $text,
                    'code' => $code,
                    'locale' => $locale
                );
            }
        }
        $this->_storeUntranslated($untranslatedPhrases);

        Varien_Profiler::stop(EW_UntranslatedStrings_Helper_Data::PROFILER_KEY);
        Varien_Profiler::stop(__CLASS__ . '::' . __FUNCTION__);
    }

    /**
     * Check for translation gap before returning
     *
     * @param string $text
     * @param string $code
     * @return string
     */
    protected function _getTranslatedString($text, $code)
    {
        if(Mage::helper('ew_untranslatedstrings')->isEnabled()) {
            $this->_checkTranslatedString($text, $code);
        }

        return parent::_getTranslatedString($text, $code);
    }

    /**
     * Rewrite to allow optional key = value in data
     * as well as optionally disabling developer mode check
     *
     * @param array $data
     * @param string $scope
     * @return Mage_Core_Model_Translate
     */
    protected function _addData($data, $scope, $forceReload=false)
    {
        foreach ($data as $key => $value) {
            // BEGIN EDIT: conditionally exclude matching key value pairs
            if(!$this->getAllowMatchingKeyValuePairs()) {
                if ($key === $value) {
                    continue;
                }
            }
            // END EDIT
            $key    = $this->_prepareDataString($key);
            $value  = $this->_prepareDataString($value);
            if ($scope && isset($this->_dataScope[$key]) && !$forceReload ) {
                /**
                 * Checking previos value
                 */
                $scopeKey = $this->_dataScope[$key] . self::SCOPE_SEPARATOR . $key;
                if (!isset($this->_data[$scopeKey])) {
                    if (isset($this->_data[$key])) {
                        $this->_data[$scopeKey] = $this->_data[$key];
                        /**
                         * Not allow use translation not related to module
                         */
                        if (Mage::getIsDeveloperMode()) {
                            // BEGIN EDIT: conditionally exclude module mismatch translations
                            if(!$this->getAllowLooseDevModuleMode()) {
                                unset($this->_data[$key]);
                            }
                            // END EDIT
                        }
                    }
                }
                $scopeKey = $scope . self::SCOPE_SEPARATOR . $key;
                $this->_data[$scopeKey] = $value;
            }
            else {
                $this->_data[$key]     = $value;
                $this->_dataScope[$key]= $scope;
            }
        }
        return $this;
    }

    protected function _storeUntranslated(array $phrases) {
        foreach($phrases as $phrase) {
            $locale = $phrase['locale'];

            //get array of all locales from registry or create new
            $strings = array();
            if(Mage::registry(self::REGISTRY_KEY)) {
                $strings = Mage::registry(self::REGISTRY_KEY);
                Mage::unregister(self::REGISTRY_KEY); //we're going to set it again in a minute
            }

            //get locale specific section of registry array
            $localeStrings = isset($strings[$locale]) ? $strings[$locale] : array();

            $text = $phrase['text'];
            $code = $phrase['code'];

            $codeParts = explode(Mage_Core_Model_Translate::SCOPE_SEPARATOR, $code);
            $module = $codeParts[0];

            //add new entry
            $localeStrings[] = array(
                'code' => $code,
                'module' => $module,
                'text' => $text,
                'store_id' => Mage::app()->getStore()->getId(),
                'locale' => $locale,
                'url' => Mage::helper('core/url')->getCurrentUrl()
            );

            $strings[$locale] = $localeStrings; //update "big" array

            //whether new or just augmented, set registry key again
            Mage::register(self::REGISTRY_KEY, $strings);
        }
    }
}