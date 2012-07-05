<?php

/**
 * Allow user to switch langage and redirect to his profil
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class IndexController extends App_Frontend_Controller {
    /* Common models 
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        parent::init();
    }

    /**
     * index action, check if the user has connected, 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function indexAction() {

        if (BaseUser::isLogged()) {
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'wall', 'action' => 'index'), 'default', true));
        } else {
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
        }
    }

    /**
     * Switch Langage  , call function static switchLang
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function changelangAction() {
        
        $url = App_Utilities::switchLang($this->_getParam('language'));
        $this->_redirect($url);
    }

   

}