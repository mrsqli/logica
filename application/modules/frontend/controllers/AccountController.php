<?php

/**
 * Profil Controller To menage profil User
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class AccountController extends App_Frontend_Controller {

    protected $_memberModel;
    protected $_idMember;
    protected $_rvCityModel;
    protected $_userModel;

    /* Common models instantiation
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        parent::init();
        $this->_userModel = new BackofficeUser();
        $this->_memberModel = new Member();
        $this->_idMember = App_Utilities::getIdMember();
        $this->_rvCityModel = new ReferenceValue();
    }

    /** Diplay all information about user connected
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     *  @param <empty>
     * @return <empty>
     */
    public function displayAction() {

        $this->title = 'Display your account';
        $row = $this->_memberModel->getMember($this->_idMember);
        $this->view->item = $row;
    }

    /**
     * Edit action, Allow the User to update his profil 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function editAction() {

        $this->title = 'Edit your account';
        $form = new ProfileForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $data = $form->getValues();
                $value_id = $this->_rvCityModel->searchCity($form->getValue('name'));
                if ($value_id) {
                    $data['rv_city_id'] = $value_id;
                } else {
                    $data['rv_city_id'] = $this->_rvCityModel->addCity($form->getValue('name'));
                }
                $this->_memberModel->updateMember($data);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'account', 'action' => 'display'), 'default', true));
            }
        } else {
            $row = $this->_memberModel->getMember($this->_idMember);
            $form->populate($row->toArray());
            $this->view->item = $row;
        }
        $this->view->form = $form;
    }

    /**
     * Change password action, Allow the User to change his password 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function changepwAction() {

        $this->title = 'Change password';
        $form = new ChangePasswordForm();

        $user = Zend_Auth::getInstance()->getIdentity();

        if ($user->id == 1) {
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'account', 'action' => 'display'), 'default', true));
        }

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $$this->_userModel->changePassword($form->getValue('password'));
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'account', 'action' => 'display'), 'default', true));
            } else {
                $form->populate($formData);
            }
        }
        $this->view->form = $form;
    }

    /**
     * Logout action, Allow the User to logout
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function logoutAction() {

        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
    }

    /**
     * Update description of member profil
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function updateprofildescriptionAction() {
        $this->title = 'update Profile';
        $form = new ProfilDescription();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $fullFilePath = $form->image_url->receive();
                $pathparts = pathinfo($form->image_url->getFileName());
                $data[image_url] = $pathparts['basename'];
                $this->_memberModel->updateProfilDescription($data);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'account', 'action' => 'edit'), 'default', true));
            } else {
                $form->populate($formData);
            }
        } else {
            $form->populate($this->_memberModel->findById($this->_idMember)->toArray());
        }
        $this->view->form = $form;
    }

}