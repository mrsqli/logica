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
        // Créons un paginateur pour cette requête

        if ($filter != false) {
            $this->_helper->layout->disableLayout();
            //$results = $this->_sessionMember->mycontact;
        } else {
            //   $results = $this->_contactModel->getMy_Contact(App_Utilities::getIdMember(), $filter);
            //  $this->_sessionMember->mycontact = $results;
        }
        $results = $this->_contactModel->getMy_Contact(App_Utilities::getIdMember(), $filter);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->mycontacts = $paginator;
        $this->view->filter = $filter;
    }

}