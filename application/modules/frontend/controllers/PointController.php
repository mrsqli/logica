<?php

/**
 * Allow the user to add in web site and login
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class PointController extends App_Frontend_Controller {

    protected $_iniConfig;
    protected $_userModel;
    protected $_urlAliasModel;
    protected $_memberModel;
    protected $_memberConnectedModel;
    protected $_companyModel;
    protected $_companyMemberModel;
    protected $_backOfficeUserGroupModel;
    protected $_tagProfilModel;
    protected $_pointModel;
    protected $_pointMemberModel;

    public function init() {
        parent::init();

        $this->_iniConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $this->_userModel = new BackofficeUser();
        $this->_urlAliasModel = new UrlAlias();
        $this->_memberModel = new Member();
        $this->_memberConnectedModel = new Member();
        $this->_companyModel = new Company();
        $this->_companyMemberModel = new CompanyMember();
        $this->_backOfficeUserGroupModel = new BackofficeUserGroup();
        $this->_tagProfilModel = new App_Tag_Manage_TagProfil();
        $this->_point = new Point();
        $this->_pointMemberModel = new PointMembre();
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function sendmsgsponsorAction() {
        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {

            $firstname = $this->_getParam('firstname');
            $lastname = $this->_getParam('lastname');
            $email = $this->_getParam('email');

            if ($this->_userModel->checkEmail($email))
                $this->view->emailNotExsiste = false;

            else {
                $this->view->emailNotExsiste = true;
                $data['lastname'] = $lastname;
                $data['firstname'] = $firstname;
                $data['username'] = $email;
                $data['email'] = $email;
                $data['groups'] = array($this->_iniConfig->groups->members->memberProspect);


                $userIdBO = $this->_userModel->save($data);
                $userId = $this->_memberModel->addMember($userIdBO, App_Utilities::getIdMember());

                $hashconfirm = md5(time() . $userId);
                $this->_memberModel->saveTextConfirm($userId, $hashconfirm);
                $this->_urlAliasModel->saveUserAlias($userId);

                $urlValidation = 'front.snb.local/point/validation/code/' . $hashconfirm;

                $this->_memberConnectedModel = $this->_memberConnectedModel->getMember(App_Utilities::getIdMember());
                $emailData = array('firstname' => $this->_memberConnectedModel->first_name,
                    'lastname' => $this->_memberConnectedModel->last_name,
                    'email' => $email,
                    'email_friend' => $this->_memberConnectedModel->email,
                    'lien' => $urlValidation
                );
                //var_dump($emailData);die;
                Zend_Controller_Action_HelperBroker::getStaticHelper('mailer')->sendMail(
                        $email, $lastname, 'Demande de confirmation de parrainage', $emailData, 'email_parrainage', false, false, 'Demande de confirmation de parrainage', 'Bonjour ' . $firstname);
            }
        }
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function validationAction() {
        $code = $this->_getParam('code');


        if (!isset($code) || empty($code)) {
            //msg d'erreur
            die('here');
            $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'user', 'action' => 'login'), 'default', true));
        }
        $member = $this->_memberModel->codeIsValide($code);
        if ($member == null)
            die('Action:Validation, Controleur:Point');
        else
        if ($member->validate == 1)
            die('deja valider'); //deja valider son compte
        else {
            $form = new UserProspectParrainForm();

            if ($this->getRequest()->isPost()) {

                if ($form->isValid($this->getRequest()->getPost())) {
                    $data = $form->getValues();

                    $companyId = $this->_companyModel->addCompany($data['raisonsociale']);
                    $this->_companyMemberModel->addMemberInCompany($companyId, $member['member_id'], $data['status']);
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


                    $this->_memberConnectedModel = $this->_memberConnectedModel->getMember($member['sponsor_parent_id']);

                    $emailData = array('firstname' => $this->_memberConnectedModel->first_name,
                        'lastname' => $this->_memberConnectedModel->last_name,
                        'email_friend' => $this->_memberConnectedModel->email,
                    );
                    Zend_Controller_Action_HelperBroker::getStaticHelper('mailer')->sendMail(
                            $this->_memberConnectedModel->email, $this->_memberConnectedModel->email, 'Points gagnés', $emailData, 'email_points', false, false, 'points gagnés', 'Bonjour ' . $this->_memberConnectedModel->first_name);

                    $data = array('member_id' => $member['sponsor_parent_id'], 'action_id' => 1);
                    $this->_pointMemberModel->insert($data);
                    $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'point', 'action' => 'msgvalidation'), 'default', true));
                }
            }
            $this->view->form = $form;
        }
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function msgvalidationAction() {
        
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function becomepremiumAction() {

        $member = $this->_memberModel->getMember(App_Utilities::getIdMember());
        $this->_memberModel->validateInscription($member['member_id']);
        $this->_backOfficeUserGroupModel->updateGroupforUser($member['backoffice_users_id'], $this->_iniConfig->groups->members->memberNotVerify);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function becomeverifyAction() {
        $this->_memberModel->validateInscription($member['member_id']);
        $this->_backOfficeUserGroupModel->updateGroupforUser($member['backoffice_users_id'], $this->_iniConfig->groups->members->memberNotVerify);
    }

}