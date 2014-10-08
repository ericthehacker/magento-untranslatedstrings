<?php

class EW_UntranslatedStrings_Model_Core_Translate extends Mage_Core_Model_Translate
{
    public function hasTranslation($text, $code) {
        $keys = array_keys($this->getData());
        sort($keys);

        if (array_key_exists($code, $this->getData())) {
            return true;
        }
        elseif (array_key_exists($text, $this->getData())) {
            return true;
        }

        return false;
    }

    protected function _getTranslatedString($text, $code)
    {
        $translated = '';
        if (array_key_exists($code, $this->getData())) {
            $translated = $this->_data[$code];
        }
        elseif (array_key_exists($text, $this->getData())) {
            $translated = $this->_data[$text];
        }
        else {
            $translated = $text;
            //EDIT: add call to write untranslated
            $this->_writeUntranslated($code, $text);
            //END EDIT
        }
        return $translated;
    }

    protected function _writeUntranslated($code, $text) {
        if(!Mage::helper('ew_untranslatedstrings')->isEnabled()) {
            return;
        }

        /* @var $resource EW_UntranslatedStrings_Model_Resource_String */
        $resource = Mage::getResourceModel('ew_untranslatedstrings/string');

        $codeParts = explode(Mage_Core_Model_Translate::SCOPE_SEPARATOR, $code);
        $module = $codeParts[0];

        $resource->writeUntranslatedString(
            $code,
            $module,
            $text,
            Mage::app()->getStore()->getId(),
            Mage::app()->getLocale()->getLocaleCode(),
            Mage::helper('core/url')->getCurrentUrl()
        );
    }
}