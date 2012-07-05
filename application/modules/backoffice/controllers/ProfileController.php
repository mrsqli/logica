<?php
/**
 * Allows user to manage their profile data
 *
 * @category backoffice
 * @package backoffice_controllers
 * @copyright company
 */

class ProfileController extends App_Backoffice_Controller
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
     * Allows users to see their dashboards
     *
     * @access public
     * @return void
     */
    public function indexAction(){
        $this->title = 'Dashboard';
    }
    
    /**
     * Allows the users to update their profiles
     *
     * @access public
     * @return void
     */
    public function editAction(){
        $this->title = 'Edit your profile';
        
        $form = new ProfileForm();
        $userModel = new BackofficeUser();
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getPost())){
                $userModel->updateProfile($form->getValues());
                $this->_helper->FlashMessenger(
                    array(
                        'msg-success' => 'Your profile was successfully updated.',
                    )
                );
                $this->_redirect('/profile/edit/');
            }
        }else{
            $user = Zend_Auth::getInstance()->getIdentity();
            $row = $userModel->findById($user->id);
            $form->populate($row->toArray());
            $this->view->item = $row;
        }
        
        $this->view->form = $form;
    }
    
    /**
     * Allows users to change their passwords
     *
     * @access public
     * @return void
     */
    public function changePasswordAction(){
        $this->title = 'Change password';
        
        $user = Zend_Auth::getInstance()->getIdentity();
        
        if($user->id == 1){
            $this->_helper->FlashMessenger(
                array(
                    'msg-warn' => 'Please don\'t change the admin password in this release.',
                )
            );
            $this->_redirect('/profile/');
        }
        
        $form = new ChangePasswordForm();
        $userModel = new BackofficeUser();
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getPost())){
                $userModel->changePassword($form->getValue('password'));
                
                $this->_helper->FlashMessenger(
                    array(
                        'msg-success' => 'Your password was successfully changed.',
                    )
                );
                $this->_redirect('/profile/');
            }
        }
        
        $this->view->form = $form;
    }
    
    /**
     * Allows users to log into the application
     *
     * @access public
     * @return void
     */
    public function loginAction(){
        $this->title = 'Login';
        
        // use the login layout
        $this->_helper->layout()->setLayout('login');
        
        $form = new LoginForm();
        if ($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())){
                $userModel = new BackofficeUser();
                if($userModel->login($form->getValue('username'), $form->getValue('password'))){
                    $session = new Zend_Session_Namespace('App.Backoffice.Controller');
                    $request = unserialize($session->request);
                    
                    if(!empty($request)){
                        $previousUri = $request->getRequestUri();
                        $this->_redirect($previousUri);
                    }else{
                        $this->_redirect('/profile/');
                    }
                }
            }
            
            $this->view->error = TRUE;
        } 
        
        $this->view->form = $form;
    }
    
    /**
     * Allows users to log out of the application
     *
     * @access public
     * @return void
     */
    public function logoutAction(){
        // log the user out
        Zend_Auth::getInstance()->clearIdentity();
        
        // destroy the session
        Zend_Session::destroy();
        
        // go to the login page
        $this->_redirect('/profile/login/');
    }
}