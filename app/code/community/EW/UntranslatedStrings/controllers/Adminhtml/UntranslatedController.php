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
        $localeStores = $this->getRequest()->getParam('locale_store');

        try {
            foreach($localeStores as $localeStore) {
                $parts = explode('-', $localeStore);

                Mage::getModel('ew_untranslatedstrings/string')->purgeTranslatedRecords($parts[1], $parts[0]);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    '%d locales successfully purged.',
                    count($localeStores)
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
        $localeStore = $this->getRequest()->getParam('locale_store');
        $parts = explode('-', $localeStore);

        try {
            if(empty($parts)) {
                throw new Mage_Adminhtml_Exception(
                    $this->__(
                        'No locale supplied.'
                    )
                );
            }

            Mage::getModel('ew_untranslatedstrings/string')->purgeTranslatedRecords($parts[1], $parts[0]);

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Locale "%s" successfully purged.',
                    $parts[0]
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Error purging locale "%s".',
                    $ex->getMessage()
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Truncate single locale
     */
    public function truncateAction() {
        $localeStore = $this->getRequest()->getParam('locale_store');
        $parts = explode('-', $localeStore);

        try {
            if(empty($parts)) {
                throw new Mage_Adminhtml_Exception(
                    $this->__(
                        'No locale supplied.'
                    )
                );
            }

            Mage::getResourceModel('ew_untranslatedstrings/string')->truncateRecords($parts[1], $parts[0]);

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Locale "%s" successfully truncated.',
                    $parts[0]
                )
            );
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Error truncating locale "%s".',
                    $ex->getMessage()
                )
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Mass truncates strings
     */
    public function massTruncateAction() {
        $localeStores = $this->getRequest()->getParam('locale_store');

        try {
            foreach($localeStores as $localeStore) {
                $parts = explode('-', $localeStore);

                Mage::getResourceModel('ew_untranslatedstrings/string')->truncateRecords($parts[1], $parts[0]);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    '%d locales successfully truncated.',
                    count($localeStores)
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