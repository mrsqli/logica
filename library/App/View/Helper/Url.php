<?php

class App_View_Helper_Url extends Zend_View_Helper_Abstract {

//comment
    public function url(array $urlOptions = array(), $name = 'default', $reset = true, $encode = true) {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $currentModule = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        if ($currentModule != 'frontend' )
            return $router->assemble($urlOptions, $name, $reset, $encode);
        $tempPageParams = array();
        if (array_key_exists('page', $urlOptions)) {
            $controller = (isset($urlOptions['controller'])) ? $urlOptions['controller'] : 'index';
            $action = (isset($urlOptions['action'])) ? $urlOptions['action'] : 'index';
            foreach ($urlOptions as $key => $value) {
                if (!in_array($key, array('module', 'controller', 'action'))) {
                    $tempPageParams[$key] = $value;
                    unset($urlOptions[$key]);
                }
            }
        }


        $url = $router->assemble($urlOptions, $name, $reset, $encode);

        $urlAliasModel = new UrlAlias();

        $source = $urlAliasModel->getUriFromUrl($url, $this->view->baseUrl());

        $alias = $urlAliasModel->getAliasBySource($source);
        //  echo $source.'ddd';die;
        if (count($tempPageParams) > 0 && ($alias == $source)) {
            return $router->assemble($tempPageParams + $urlOptions, $name, $reset, $encode);
        }
        //  echo $this->view->baseUrl() . '/' . $alias . ((count($tempPageParams) == 0) ? '' : $this->stringifyParams($tempPageParams));die;
        return $this->view->baseUrl() . '/' . $alias . ((count($tempPageParams) == 0) ? '' : $this->stringifyParams($tempPageParams));
    }

    public function stringifyParams($params) {
        $result = '-';
        foreach ($params as $key => $value) {
            $result .='-' . $key . '-' . $value;
        }
        return $result;
    }

}

?>
