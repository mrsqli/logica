<?php

/**
 * Frontend bootstrap
 *
 * @package Frontend
 * @copyright company
 */
class Frontend_Bootstrap extends App_Bootstrap_Abstract {

    /**
     * Inits the session for the frontend
     * 
     * @access protected
     * @return void
     */
    protected function _initSession() {
        Zend_Session::start();
    }

    /**
     * Inits the Zend Paginator component
     *
     * @access protected
     * @return void
     */
    protected function _initPaginator() {
        Zend_Paginator::setDefaultScrollingStyle(App_DI_Container::get('ConfigObject')->paginator->scrolling_style);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                'default.phtml'
        );
    }

    /**
     * Initializes the view helpers for the application
     * 
     * @access protected
     * @return void     
     */
    protected function _initViewHelpers() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (NULL === $viewRenderer->view) {
            $viewRenderer->initView();
        }

        $viewRenderer->view->addHelperPath('App/Frontend/View/Helper', 'App_Frontend_View_Helper');
    }

   

}