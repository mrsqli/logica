<?php

class App_Controller_Action_Helper_Redirector extends Zend_Controller_Action_Helper_Redirector {

    public function setGotoRoute(array $urlOptions = array(), $name = null, $reset = false, $encode = true) {

        $router = $this->getFrontController()->getRouter();
        $currentModule = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        if ($currentModule !== 'frontend') {
            $url = $router->assemble($urlOptions, $name, $reset, $encode);
            $this->_redirect($url);
        }

        $view = Zend_Controller_Front::getInstance()
                ->getParam('bootstrap')
                ->getResource('view');

        $tempPageParams = array();
        if (array_key_exists('page', $urlOptions)) {
            $controller = (isset($urlOptions['controller'])) ? $urlOptions['controller'] : 'index';
            $action = (isset($urlOptions['action'])) ? $urlOptions['action'] : 'index';
            foreach ($urlOptions as $key => $value) {
                if (!in_array($key, array('module', 'controller', 'action'))
                        && !($key == 'id' && $controller == 'category' && $action == 'products' )
                        && !($key == 'brandId' && $controller == 'brand' && $action == 'brandproducts' )) {
                    $tempPageParams[$key] = $value;
                    unset($urlOptions[$key]);
                }
            }
        }
        $url = $router->assemble($urlOptions, $name, $reset, $encode);
        $urlAliasModel = new UrlAlias();

        $source = $urlAliasModel->getUriFromUrl($url, $view->baseUrl());

        $alias = $urlAliasModel->getAliasBySource($source);
        if (count($tempPageParams) > 0 && ($alias == $source)) {
            $this->_redirect($router->assemble($tempPageParams + $urlOptions, $name, $reset, $encode));
        }

        $this->_redirect($view->baseUrl() . '/' . $alias . ((count($tempPageParams) == 0) ? '' : $this->stringifyParams($tempPageParams)));
    }

}

?>
