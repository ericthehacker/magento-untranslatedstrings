<?php

class EW_UntranslatedStrings_Model_Core_Translate extends Mage_Core_Model_Translate
{
    /** @var array */
    private $_localesToCheck = null;

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
        $this->_writeUntranslated($untranslatedPhrases);

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

    protected function _writeUntranslated(array $phrases) {
        /* @var $resource EW_UntranslatedStrings_Model_Resource_String */
        $resource = Mage::getResourceModel('ew_untranslatedstrings/string');

        foreach($phrases as $phrase) {
            $text = $phrase['text'];
            $code = $phrase['code'];
            $locale = $phrase['locale'];

            $codeParts = explode(Mage_Core_Model_Translate::SCOPE_SEPARATOR, $code);
            $module = $codeParts[0];

            $resource->writeUntranslatedString(
                $code,
                $module,
                $text,
                Mage::app()->getStore()->getId(),
                $locale,
                Mage::helper('core/url')->getCurrentUrl()
            );
        }
    }
}