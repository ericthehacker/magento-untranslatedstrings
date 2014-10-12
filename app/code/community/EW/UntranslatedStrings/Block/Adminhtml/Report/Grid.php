<?php

class EW_UntranslatedStrings_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('ew_untranslatedstrings_adminhtml_report_grid');
    }

    protected function _prepareCollection() {
        /* var $collection EW_UntranslatedStrings_Model_Resource_String_Collection */
        $collection = Mage::getResourceModel('ew_untranslatedstrings/string_collection');
        $collection->joinStoreCode();
        $collection->setOrder('encounter_count', Varien_Db_Select::SQL_DESC);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('id', array(
            'header'    => $this->__('ID'),
            'align'     => 'left',
            'width'     => '75px',
            'index'     => 'id',
            'type'      => 'number'
        ));

        $this->addColumn('store_code', array(
            'header'    => $this->__('Store Code'),
            'align'     => 'left',
            'width'     => '75px',
            'index'     => 'store_code',
        ));

        $this->addColumn('untranslated_string', array(
            'header'    => $this->__('Untranslated String'),
            'align'     => 'left',
            'index'     => 'untranslated_string',
        ));

        $this->addColumn('translation_code', array(
            'header'    => $this->__('Translation Code'),
            'align'     => 'left',
            'index'     => 'translation_code',
        ));

        $this->addColumn('translation_module', array(
            'header'    => $this->__('Translation Module'),
            'align'     => 'left',
            'index'     => 'translation_module',
        ));


        $this->addColumn('locale', array(
            'header'    => $this->__('Locale'),
            'align'     => 'left',
            'index'     => 'locale',
        ));

        $this->addColumn('url_found', array(
            'header'    => $this->__('URL'),
            'align'     => 'left',
            'index'     => 'url_found',
        ));

        $this->addColumn('date_found', array(
            'header'    => $this->__('Date Found'),
            'index'     => 'date_found',
            'width'     => '175px',
            'type'      => 'datetime',
        ));

        $this->addColumn('encounter_count', array(
            'header'    => $this->__('Popularity'),
            'align'     => 'left',
            'width'     => '75px',
            'index'     => 'encounter_count',
            'type'      => 'number'
        ));

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));

        return parent::_prepareColumns();
    }
}
