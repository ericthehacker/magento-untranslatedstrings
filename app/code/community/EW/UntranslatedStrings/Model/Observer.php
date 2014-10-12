<?php

class EW_UntranslatedStrings_Model_Observer
{
    /**
     * Flush untranslated strings from registry to database.
     * Observes: controller_front_send_response_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function flushUntranslatedStrings(Varien_Event_Observer $observer) {
        if(!Mage::registry(EW_UntranslatedStrings_Model_Core_Translate::REGISTRY_KEY)) {
            return; //no strings found
        }

        $strings = Mage::registry(EW_UntranslatedStrings_Model_Core_Translate::REGISTRY_KEY);

        if(!is_array($strings)) {
            return; //something went very wrong.
        }

        Varien_Profiler::start(EW_UntranslatedStrings_Helper_Data::PROFILER_KEY);

        /* @var $resource EW_UntranslatedStrings_Model_Resource_String */
        $resource = Mage::getResourceModel('ew_untranslatedstrings/string');

        foreach($strings as $localeStrings) {
            $resource->writeUntranslatedStrings($localeStrings);
        }

        Varien_Profiler::stop(EW_UntranslatedStrings_Helper_Data::PROFILER_KEY);
    }
}