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

    /**
     * Export to CSV
     */
    public function exportCsvAction()
    {
        $fileName   = 'untranslated_strings_report.csv';
        $content    = $this->getLayout()
                           ->createBlock('ew_untranslatedstrings/adminhtml_report_grid','adminhtml_report.grid')
                           ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}