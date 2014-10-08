<?php

class EW_UntranslatedStrings_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_ENABLED = 'dev/translate/untranslated_strings_enabled';

    /**
     * Is functionality enabled?
     *
     * @return bool
     */
    public function isEnabled() {
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_ENABLED);
    }
}