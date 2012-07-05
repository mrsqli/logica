<?php

/**
 * Generic bootstrap
 * 
 * This class bootstraps the common issues in all modules 
 * while each module's Bootstrap manages its own particular 
 * configuration
 *
 * @package   application
 * @copyright company
 */
require_once 'App/Bootstrap/Abstract.php';

class Bootstrap extends App_Bootstrap_Abstract {

    /**
     * Resources to be bootstrapped first
     * 
     * @var    array    
     * @access protected
     */
    protected $_first = array(
        'Autoloader',
        'Environment'
    );

    /**
     * Resources to be bootstrapped last
     * 
     * @var    array    
     * @access protected
     */
    protected $_last = array(
        'Module',
        'ModulePaths'
    );

    /**
     * Bootstraps the Autoloader
     * 
     * @access protected
     * @return void     
     */
    protected function _initAutoloader() {
        require_once 'Zend/Loader/Autoloader.php';

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('App_');
        $loader->registerNamespace('Zend_');
        $loader->setFallbackAutoloader(TRUE);
    }

    /**
     * Includes the file with the environment constant - APPLICATION_ENV
     * If the file cannot be read or the constant isn't defined, an exception 
     * will be throwed
     * 
     * @access protected
     * @return void     
     */
    protected function _initEnvironment() {
        $file = APPLICATION_PATH . '/configs/environment.php';
        if (!is_readable($file)) {
            throw new Zend_Exception('Cannot find the environment.php file!');
        }

        require_once ($file);
        if (!defined('APPLICATION_ENV')) {
            throw new Zend_Exception('The APPLICATION_ENV constant is not defined in ' . $file);
        }

        Zend_Registry::set('IS_PRODUCTION', APPLICATION_ENV == APP_STATE_PRODUCTION);
        Zend_Registry::set('IS_DEVELOPMENT', APPLICATION_ENV == APP_STATE_DEVELOPMENT);
        Zend_Registry::set('IS_STAGING', APPLICATION_ENV == APP_STATE_STAGING);
    }

    /**
     * Store the app version in the registry
     *
     * @access protected
     * @return void
     */
    protected function _initAppVersion() {
        $configuration = App_DI_Container::get('ConfigObject');

        // Register the version of the app
        if (isset($configuration->release->version)) {
            define('APP_VERSION', $configuration->release->version);
        } else {
            define('APP_VERSION', 'unknown');
        }

        Zend_Registry::set('APP_VERSION', APP_VERSION);
    }

    /**
     * Bootstraps the current module 
     * This relies on the CURRENT_MODULE constant, if it's not defined 
     * an exception will the throwed
     * 
     * @access protected
     * @return void     
     */
    protected function _initModule() {
        if (!defined('CURRENT_MODULE')) {
            throw new Zend_Exception('The CURRENT_MODULE module constant is' .
                    ' not defined! Please check the index.php file for this module.');
        }

        $filename = APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/Bootstrap.php';
        if (is_readable($filename)) {
            require_once $filename;
            $class = ucfirst(CURRENT_MODULE . '_Bootstrap');
            if (!class_exists($class)) {
                throw new Zend_Exception('Class ' . $class . ' could not be found in file ' . $filename);
            }

            $module = new $class();
        }
    }

    /**
     * Inits the current module's paths 
     * This relies on the CURRENT_MODULE constant, if it's not defined
     * an exception will be throwed
     * 
     * @access protected
     * @return void     
     */
    protected function _initModulePaths() {
        if (!defined('CURRENT_MODULE')) {
            throw new Zend_Exception('The CURRENT_MODULE module constant is' .
                    ' not defined! Please check the index.php file for this module.');
        }

        $paths = array(
            APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/controllers',
            APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/forms',
            APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/models',
            APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/tables',
            ROOT_PATH . '/library/App/Model',
            get_include_path(),
        );

        set_include_path(implode(PATH_SEPARATOR, $paths));
    }

    /**
     * Bootstraps the front controller
     * 
     * @access protected
     * @return void     
     */
    protected function _initFrontController() {
        $front = Zend_Controller_Front::getInstance();

        $front->addModuleDirectory(APPLICATION_PATH . '/modules');
        $front->setDefaultModule(CURRENT_MODULE);
    }

    /**
     * Initializes the database connection
     * 
     * @access protected
     * @return void
     */
    protected function _initDb() {
        $config = App_DI_Container::get('ConfigObject');

        $dbAdapter = Zend_Db::factory($config->resources->db);
        Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);
        Zend_Registry::set('dbAdapter', $dbAdapter);

        Zend_Db_Table_Abstract::setDefaultMetadataCache(App_DI_Container::get('CacheManager')->getCache('default'));
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

        $viewRenderer->view->addHelperPath('App/View/Helper', 'App_View_Helper');
        $viewRenderer->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
    }

    /**
     * Initializes the action helpers for the application
     *
     * @return void
     */
    protected function _initActionHelpers() {
        // Add the possibility to log to Firebug. Example: $this->_helper->log('Message');
        Zend_Controller_Action_HelperBroker::addHelper(new App_Controller_Action_Helper_Logger());

        // Add the Flag and Flippers helper for the controllers. Example: $this->_helper->flagFlippers()
        Zend_Controller_Action_HelperBroker::addHelper(new App_Controller_Action_Helper_FlagFlippers());

        // Add the translator helper to all the modules
        Zend_Controller_Action_HelperBroker::addHelper(new App_Controller_Action_Helper_T());

        // Add the auto redirector helper to all the modules
        Zend_Controller_Action_HelperBroker::addHelper(new App_Controller_Action_Helper_Redirector());
        
        // Add the Mailer helper to all the modules
        Zend_Controller_Action_HelperBroker::addHelper(new App_Controller_Action_Helper_Mailer());
    }

    /**
     * Setup the locale based on the browser
     *
     * @return void
     */
    protected function _initLocale() {

        $locale = new Zend_Locale();

        if (!Zend_Locale::isLocale($locale, TRUE, FALSE)) {
            if (!Zend_Locale::isLocale($locale, FALSE, FALSE)) {
                throw new Zend_Locale_Exception("The locale '$locale' is no known locale");
            }

            $locale = new Zend_Locale($locale);
        }

        if ($locale instanceof Zend_Locale) {
            Zend_Registry::set('Zend_Locale', $locale);
        }
    }

    /**
     * Inits the layouts (full configuration)
     * 
     * @access protected
     * @return void
     */
    protected function _initLayout() {
        Zend_Layout::startMvc(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/layouts/');
    }

    /**
     * Inits the view paths
     *
     * Additional paths are used in order to provide a better separation
     * 
     * @access protected
     * @return void     
     */
    protected function _initViewPaths() {
        $this->bootstrap('Layout');

        $view = Zend_Layout::getMvcInstance()->getView();

        $view->addScriptPath(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/forms/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/paginators/');
        $view->addScriptPath(APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/views/scripts/');
        $view->addScriptPath(ROOT_PATH . '/library/App/Mail/Templates/');
    }

    /**
     * Initialize the ZFDebug Widget
     *
     * @return void
     */
    protected function _initZFDebug() {
        $config = App_DI_Container::get('ConfigObject');

        $dbAdapter = Zend_Registry::get('dbAdapter');

        $options = array(
            'plugins' => array(
                'Variables',
                'Html',
                'Database' => array('adapter' => array('default' => $dbAdapter)),
                'File' => array('basePath' => ROOT_PATH),
                'Memory',
                'Time',
                'Registry',
                'Exception'
            )
        );

        if ($config->zfdebug->show_cache_panel) {
            $defaultCache = App_DI_Container::get('CacheManager')->getCache('default');

            $options['plugins']['Cache'] = array(
                'backend' => array(
                    $defaultCache->getBackend(),
                )
            );
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $frontController = Zend_Controller_Front::getInstance()->registerPlugin($debug);
    }

    /**
     * Initialize and register the plugins
     * 
     * @access protected
     * @return void
     */
    protected function _initPlugins() {
        $frontController = Zend_Controller_Front::getInstance();

        // Application_Plugin_VersionHeader sends a X-SF header with the system version for debugging
        $frontController->registerPlugin(new App_Plugin_VersionHeader());
    }

    /**
     * Initialize the routes
     *
     * @return void
     */
//    protected function _initRouter() {
//        $router = new Zend_Controller_Router_Rewrite();
//
//        $routes = new Zend_Config_Xml(APPLICATION_PATH . '/configs/' . CURRENT_MODULE . '_routes.xml');
//        $router->addConfig($routes);
//
//
//        $front = Zend_Controller_Front::getInstance();
//        $front->setRouter($router);
//
//        Zend_Registry::set('Router', $router);
//    }

    /**
     * Initialize the translation system
     *
     * @return void
     */
    protected function _initTranslator() {
        $this->bootstrap('Locale');

        //Extract some info from the request
        $local = Zend_Registry::get('Zend_Locale')->getLanguage();

        $langage = new Zend_Session_Namespace('Member');

        if (!isset($langage->choosedLanguage)) {
            $lang = $langage->choosedLanguage = $local;
        } else {
            $lang = $langage->choosedLanguage;
        }

        $translationFile = ROOT_PATH . '/library/App/Translations/' . $lang . '.mo';

        //Check if the translations file is available, if not fallback default to english
        if (!file_exists($translationFile)) {
            $translationFile = APPLICATION_PATH . '/modules/' . CURRENT_MODULE . '/translations/en.mo';
        }

        $options = array(
            'adapter' => 'gettext',
            'content' => $translationFile,
            'locale' => $lang,
            'disableNotices' => App_DI_Container::get('ConfigObject')->translations->disable_notices,
            'logMessage' => "Missing translation: %message%",
            'logUntranslated' => App_DI_Container::get('ConfigObject')->translations->log_missing_translations
        );

        //Create a zend_log for missing translations
        if (App_DI_Container::get('ConfigObject')->translations->log_missing_translations) {
            $pathLog = ROOT_PATH . '/logs/' . CURRENT_MODULE . '/missing_translations/' . date('Ymd') . '_' . $lang . '.log';
            $writer = new Zend_Log_Writer_Stream($pathLog);
            $logger = new Zend_Log($writer);

            $options['log'] = $logger;
        }

        $translate = new Zend_Translate($options);
        // $translate->setLocale($lang);
        Zend_Registry::set('Zend_Translate', $translate);

        Zend_Validate_Abstract::setDefaultTranslator($translate);
        Zend_Form::setDefaultTranslator($translate);
    }

    /**
     * Initialize the Flag and Flipper System
     *
     * @return void
     */
    protected function _initFlagFlippers() {
        $this->bootstrap('ModulePaths');

        $path = realpath(APPLICATION_PATH . '/../logs/' . CURRENT_MODULE . '/flagflippers.log');
        $logger = new Zend_Log(new Zend_Log_Writer_Stream($path));

        if (!Zend_Registry::get('IS_PRODUCTION')) {
            $logger->addWriter(new Zend_Log_Writer_Firebug());
        }

        Zend_Registry::set('Zend_Log_FlagFlippers', $logger);

        App_FlagFlippers_Manager::load();
    }

    /**
     * Initialize and configure the jQuery options
     *
     * @return void
     */
    protected function _initJQuery() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->jQuery()->addStylesheet('/css/jquery-ui.css');
        $view->jQuery()->setLocalPath('/js/jquery.min.js');
        $view->jQuery()->setUiLocalPath('/js/jquery-ui.min.js');
    }

    /**
     * Init Request
     * @return Zend_Controller_Request_Http
     */
    protected function _initRequest() {

        if (CURRENT_MODULE == 'frontend') {
            $front = Zend_Controller_Front::getInstance();
            $request = new Zend_Controller_Request_Http;
            $front->setRequest($request);
            $router = $front->getRouter();
            $route = new Zend_Controller_Router_Route_Static(
                            'dsrpc.html',
                            array('controller' => 'wsclientpoint', 'action' => 'index')
            );
            $rewriteUrlArray = array();
            $rewriteUrlArray[''] = array('controller' => 'index', 'action' => 'index');

            $auth = Zend_Auth::getInstance();

            foreach ($rewriteUrlArray as $key => $value) {
                $router->addRoute($key, new Zend_Controller_Router_Route_Static(
                                $key, array('controller' => $value['controller'], 'action' => $value['action'])
                ));
            }
            $urlAliasModel = new UrlAlias();
            $url = $request->getRequestUri();

            /**
             * If the alias contains a pagination
             */
            $pagePosition = strpos($url, '--page-');
            $pageParams = array();
            if ($pagePosition !== false) {
                $stringParams = substr($url, $pagePosition + 2, strlen($url));
                $pageParams = explode('-', $stringParams);
                $url = substr($url, 0, $pagePosition);
            }
            /**
             * /If
             */
            $alias = $urlAliasModel->getUriFromUrl($url, $request->getBaseUrl());
            $aliasItem = $urlAliasModel->getByAlias($alias);
            //var_dump($aliasItem->params);die;
            if ($aliasItem !== false) {
                $paramArray = (Array) json_decode($aliasItem->params);
                if (count($pageParams) > 0) {
                    $alias .= '-';
                    $i = 0;
                    foreach ($pageParams as $param) {
                        $alias .= '-' . $param;
                        if ($i % 2 == 0 && $pageParams[$i + 1] != '') {
                            $paramArray[$param] = $pageParams[$i + 1];
                        }
                        $i++;
                    }
                }
                $router->addRoute($alias, new Zend_Controller_Router_Route_Static(
                                $alias, $paramArray
                ));
            }
            /**
             * Rewrite the captchas path
             */
            $route = new Zend_Controller_Router_Route(
                            'captcha/:filename',
                            array(
                                'controller' => 'file',
                                'action' => 'getcaptcha'
                            )
            );
            $router->addRoute('captchas', $route);

            return $request;
        }
    }

    /**
     * Runs the application
     * 
     * @access public
     * @return void  
     */
    public function runApp() {
        $front = Zend_Controller_Front::getInstance();
        $front->dispatch();
    }

}
