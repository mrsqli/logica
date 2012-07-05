<?php
/**
 * Default entry point in the application
 *
 * @category backoffice
 * @package backoffice_controllers
 * @copyright company
 */

class IndexController extends App_Backoffice_Controller
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
        $this->_redirect('/profile/');
    }
}