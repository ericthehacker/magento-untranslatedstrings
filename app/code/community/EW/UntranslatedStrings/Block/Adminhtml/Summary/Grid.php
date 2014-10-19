<?php

class EW_UntranslatedStrings_Block_Adminhtml_Summary_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('ew_untranslatedstrings_adminhtml_summary_grid');
    }

    protected function _prepareCollection() {
        /* var $collection EW_UntranslatedStrings_Model_Resource_String_Collection */
        $collection = Mage::getResourceModel('ew_untranslatedstrings/string_collection');
        $collection->configureSummary();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('locale', array(
            'header'    => $this->__('Locale'),
            'align'     => 'left',
            'index'     => 'locale',
        ));

        $this->addColumn('string_count', array(
            'header'    => $this->__('Strings Count'),
            'align'     => 'left',
            'width'     => '75px',
            'index'     => 'string_count',
            'type'      => 'number'
        ));

//        $this->addColumn('top_strings', array(
//            'header'    => $this->__('Top Strings'),
//            'align'     => 'left',
//            'index'     => 'top_strings',
//            'type'      => 'wrapline'
//        ));



        $this->addExportType('*/*/exportCsv', $this->__('CSV'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('locale');
        $this->setMassactionIdFilter('locale');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('locales');

        $this->getMassactionBlock()->addItem('purge', array(
            'label'=> $this->__('Purge'),
            'url'  => $this->getUrl(
                '*/*/massPurge'
            ),
            'confirm' => $this->__('This will purge the log of untranslated strings which are now translated. Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('truncate', array(
            'label'=> $this->__('Truncate'),
            'url'  => $this->getUrl(
                '*/*/massTruncate'
            ),
            'confirm' => $this->__('This will remove all untranslated string logs for the selected locale(s). Are you sure?')
        ));
    }
}
