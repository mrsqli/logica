<?php

/**
 * Allow the user to add in web site and login
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class UserController extends App_Frontend_Controller {

    protected $_iniConfig;
    protected $_userModel;
    protected $_urlAliasModel;
    protected $_memberModel;
    protected $_authentification;
    protected $_companyModel;
    protected $_companyMemberModel;
    protected $_backOfficeUserGroupModel;
    protected $_tagProfilModel;

    /* Common models instantiation
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        parent::init();
        $this->_iniConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $this->_userModel = new BackofficeUser();
        $this->_urlAliasModel = new UrlAlias();
        $this->_memberModel = new Member();
        $this->_authentification = new LoginAttempt();
        $this->_companyModel = new Company();
        $this->_companyMemberModel = new CompanyMember();
        $this->_backOfficeUserGroupModel = new BackofficeUserGroup();
        $this->_tagProfilModel = new App_Tag_Manage_TagProfil();
    }

    /*
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function indexAction() {
        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
    }

    /**
     * add new user
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function addAction() {

        if (BaseUser::isLogged()) {
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'wall', 'action' => 'index'), 'default', true));
        }
        $this->title = 'Add a new user';

        $form = new UserProspectForm();
        //$form = new UserAddForm();


        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {
                $data = $form->getValues();

                $data['username'] = $data['email'];
                $data['groups'] = array($this->_iniConfig->groups->members->memberProspect);


                $userIdBO = $this->_userModel->save($data);
                $userId = $this->_memberModel->addMember($userIdBO);
                $companyId = $this->_companyModel->addCompany($data['raisonsociale']);
                $this->_companyMemberModel->addMemberInCompany($companyId, $userId, $data['status']);
               // $this->_tagProfilModel->AddTagCompany($data['raisonsociale']);
                $hashconfirm = md5(time() . $userId);
                $this->_memberModel->saveTextConfirm($userId, $hashconfirm);
                $this->_urlAliasModel->saveUserAlias($userId);

                $urlValidation = 'front.snb.local/user/validation/code/' . $hashconfirm;


                $emailData = array('firstname' => $data['firstname'],
                    'emailClient' => $data['email'],
                    'lien' => $urlValidation
                );
                //var_dump($emailData);die;
                Zend_Controller_Action_HelperBroker::getStaticHelper('mailer')->sendMail(
                        $data['email'], $data['lastname'], 'Confirmation de votre inscription', $emailData, 'email_validation', false, false, 'Confirmation de votre inscription', 'Bonjour ' . $data['firstname']);

                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
            }
        }
        $this->view->form = $form;
    }

    /**
     * validation action, Allow user to valide his authentification
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function validationAction() {

        $code = $this->_getParam('code');

        if (!isset($code) || empty($code)) {
            //msg d'erreur
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
        }
        $member = $this->_memberModel->codeIsValide($code);
        if ($member == null)
            ; // lien errone, member n'exsiste pas
        else
        if ($member->validate == 1)
            ; //deja valider son compte
        else {
            $password = App_Utilities::Genere_Password($this->_iniConfig->member->password->size);
            $this->_memberModel->validateInscription($member['member_id']);
            $this->_backOfficeUserGroupModel->updateGroupforUser($member['backoffice_users_id'], $this->_iniConfig->groups->members->memberNotVerify);
            $user = $this->_userModel->updatePassword($member['backoffice_users_id'], $password);

            $emailData = array('firstname' => $member['first_name'],
                'emailClient' => $member['email'],
                'password' => $password
            );
            //var_dump($emailData);die;
            Zend_Controller_Action_HelperBroker::getStaticHelper('mailer')->sendMail(
                    $member['email'], $member['last_name'], 'Confirmation de votre inscription', $emailData, 'email_inscription', false, false, 'Confirmation de votre inscription', 'Bonjour ' . $member['first_name']);

          //  $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
        }
    }

    /**
     * login action, Allow the User to connect
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function loginAction() {

        if (BaseUser::isLogged()) {
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'wall', 'action' => 'index'), 'default', true));
        }

        $this->title = 'Login';
        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            if (($registration = $this->getRequest()->getParam('inscription', false)))
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'add'), 'default', true));
            if ($form->isValid($this->getRequest()->getPost())) {
                if ($this->_userModel->login($form->getValue('username'), $form->getValue('password'))) {
                    $member = App_Utilities::setMember_Registry();
                    if ($member->validate == 1) {
                        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'wall', 'action' => 'index'), 'default', true));
                    } else {
                        Zend_Auth::getInstance()->clearIdentity();
                        Zend_Session::destroy();
                        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
                    }
                }
            } else {
                $this->_authentification->addFailedLogin();
            }
            $this->view->error = TRUE;
        }
        $this->view->form = $form;
    }

}