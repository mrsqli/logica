<?php
/**
 * Bootstrap class for CLI components
 *
 * @category backoffice
 * @package backoffice
 * @subpackage backoffice_bootstrap
 * @copyright company
 */

class CliBootstrap extends App_Bootstrap_Abstract
{
    /**
     * Inits the layouts (full configuration)
     * 
     * @access protected
     * @return void
     */
    protected function _initLayout()
    {
        Zend_Layout::startMvc(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/layouts/');
        
        $view = Zend_Layout::getMvcInstance()->getView();
    }
    
    /**
     * Initialize the routes
     *
     * @return void
     */
    protected function _initRouter(){
        $routes = new Zend_Config_Xml(APPLICATION_PATH . '/configs/frontend_routes.xml');
        $router = new Zend_Controller_Router_Rewrite();
        $router->addConfig($routes);
        
        $front = Zend_Controller_Front::getInstance();
        $front->setRouter($router);
    }
    
    /**
     * Inits the view paths
     *
     * Additional paths are used in order to provide a better separation
     * 
     * @access protected
     * @return void     
     */
    protected function _initViewPaths()
    {
        $this->bootstrap('Layout');
        
        $view = Zend_Layout::getMvcInstance()->getView();
        
        $view->addScriptPath(APPLICATION_PATH . '/modules/frontend/views/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/frontend/views/forms/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/frontend/views/paginators/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/frontend/views/scripts/');
        $view->addScriptPath(ROOT_PATH . '/library/App/Mail/Templates/');
    }
    
    /**
     * Initializes the view helpers for the application
     * 
     * @access protected
     * @return void     
     */
    protected function _initViewHelpers()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (NULL === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        
        $viewRenderer->view->addHelperPath('App/View/Helper', 'App_View_Helper');
    }
}