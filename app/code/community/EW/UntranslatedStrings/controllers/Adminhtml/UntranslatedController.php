<?php

class EW_UntranslatedStrings_Adminhtml_UntranslatedController extends Mage_Adminhtml_Controller_Action
{
    public function reportAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Enforce ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'report':
                return Mage::getSingleton('admin/session')->isAllowed('report/ew_untranslatedstrings/report');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('report/ew_untranslatedstrings');
                break;
        }
    }
}