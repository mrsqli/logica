<?php

/**
 * Allow the user to add in web site and login
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class ContactController extends App_Frontend_Controller {

    protected $_memberModel;
    protected $_contactModel;
    protected $_sessionMember;

    public function init() {
        parent::init();

        $this->_memberModel = new Member();
        $this->_contactModel = new Contact();
        $this->_sessionMember = new Zend_Session_Namespace('Member');
    }

    public function listmycontactAction() {

        $filter = $this->_getParam('filter', false);

        if ($filter != false) {
            $this->_helper->layout->disableLayout();
            $results = $this->_sessionMember->mycontact;
        } else {
            $results = $this->_contactModel->getMy_Contact(App_Utilities::getIdMember(), $filter);
            $this->_sessionMember->mycontact = $results;
        }

        //var_dump($results);die;
        $this->view->mycontacts = $results;
        $this->view->filter = $filter;
    }

}