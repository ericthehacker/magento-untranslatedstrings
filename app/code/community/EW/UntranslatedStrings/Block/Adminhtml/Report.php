<?php

class EW_UntranslatedStrings_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'Report';
        $this->_blockGroup = 'ew_untranslatedstrings';
        $this->_controller = 'adminhtml_report';
        $this->_headerText = Mage::helper('ew_untranslatedstrings')->__('Untranslated Strings');
        parent::__construct();
        $this->_removeButton('add');
    }
}