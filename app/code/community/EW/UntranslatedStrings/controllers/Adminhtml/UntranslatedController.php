<?php

class EW_UntranslatedStrings_Adminhtml_UntranslatedController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Summary view of untranslated strings
     */
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Full untranslated strings report
     */
    public function reportAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Mass purges strings
     */
    public function massPurgeAction() {
        $locales = $this->getRequest()->getParam('locales');

        try {
            foreach($locales as $locale) {
                Mage::getModel('ew_untranslatedstrings/string')->purgeTranslatedRecords($locale);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    '%d locales successfully purged.',
                    count($locales)
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__(
                    'Error purging locales: %s',
                    $ex->getMessage()
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Purge single locale
     */
    public function purgeAction() {
        $locale = $this->getRequest()->getParam('locale');

        try {
            if(empty($locale)) {
                throw new Mage_Adminhtml_Exception(
                    $this->__(
                        'No locale supplied.'
                    )
                );
            }

            Mage::getModel('ew_untranslatedstrings/string')->purgeTranslatedRecords($locale);

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Locale "%s" successfully purged.',
                    $locale
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Error purging locale "%s".',
                    $locale
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Truncate single locale
     */
    public function truncateAction() {
        $locale = $this->getRequest()->getParam('locale');

        try {
            if(empty($locale)) {
                throw new Mage_Adminhtml_Exception(
                    $this->__(
                        'No locale supplied.'
                    )
                );
            }

            Mage::getResourceModel('ew_untranslatedstrings/string')->truncateRecords($locale);

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Locale "%s" successfully truncated.',
                    $locale
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Error truncating locale "%s".',
                    $locale
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Mass truncates strings
     */
    public function massTruncateAction() {
        $locales = $this->getRequest()->getParam('locales');

        try {
            foreach($locales as $locale) {
                Mage::getResourceModel('ew_untranslatedstrings/string')->truncateRecords($locale);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    '%d locales successfully truncated.',
                    count($locales)
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__(
                    'Error truncating locales: %s',
                    $ex->getMessage()
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Enforce ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'index':
                return Mage::getSingleton('admin/session')->isAllowed('report/ew_untranslatedstrings/activity_summary');
                break;
            case 'report':
                return Mage::getSingleton('admin/session')->isAllowed('report/ew_untranslatedstrings/activity_report');
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