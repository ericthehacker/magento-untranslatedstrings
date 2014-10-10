<?php

class EW_UntranslatedStrings_Adminhtml_UntranslatedController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}