<?php
/**
 * Form for deleting multiple items at once
 *
 *
 * @category backoffice
 * @package backoffice_forms
 * @copyright company
 */

class DeleteMultipleForm extends App_Backoffice_Form
{
    /**
     * Overrides init() in Zend_Form
     * 
     * @access public
     * @return void
     */
    public function init() {
        // init the parent
        parent::init();
        
        // set the form's method
        $this->setMethod('post');
        
        // form fields
    }
}