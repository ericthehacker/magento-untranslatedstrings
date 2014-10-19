<?php

class EW_UntranslatedStrings_Block_Adminhtml_Summary extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'Summary';
        $this->_blockGroup = 'ew_untranslatedstrings';
        $this->_controller = 'adminhtml_summary';
        $this->_headerText = Mage::helper('ew_untranslatedstrings')->__('Untranslated Strings Summary');
        parent::__construct();
        $this->_removeButton('add');
        $this->_addButton(
            'full_report',
            array(
                'label'   => $this->__('View Full Report'),
                'onclick' => 'window.location=\''. $this->getUrl('*/*/report') .'\'',
            )
        );
    }
}