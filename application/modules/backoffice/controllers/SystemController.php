<?php
/**
 * Allow the admins to manage critical info, users, groups, permissions, etc.
 *
 * @category backoffice
 * @package backoffice_controllers
 * @copyright company
 */

class SystemController extends App_Backoffice_Controller
{
    /**
     * Overrides Zend_Controller_Action::init()
     *
     * @access public
     * @return void
     */
    public function init(){
        // init the parent
        parent::init();
    }
    
    /**
     * Controller's entry point
     *
     * @access public
     * @return void
     */
    public function indexAction(){
    }
    
    /**
     * Theme example page
     *
     * @access public
     * @return void
     */
    public function exampleAction(){
    }
}