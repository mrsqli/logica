<?php

/**
 * Profil Controller To menage profil User
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class ProfileController extends App_Frontend_Controller {

    protected $_experienceModel;
    protected $_studyModel;
    protected $_skillsModel;
    protected $_memberModel;
    protected $_companyModel;
    protected $_companyMemberModel;
    protected $_idMember;
    protected $_tagProfilModel;
    protected $_rvCompany;
    protected $_rvSchool;
    protected $_rvDomain;
    protected $_rvSkill;

    /* function profile to display experience,study and skills of member connected
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    protected function profile() {

        $this->view->experience = $this->_experienceModel->getExperienceByMember($this->_idMember);
        $this->view->study = $this->_studyModel->getStudyByMember($this->_idMember);
        $this->view->skills = $this->_skillsModel->getSkillsByMember($this->_idMember);
    }

    /* Common models instantiation
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        parent::init();
        $this->_experienceModel = new Experience();
        $this->_studyModel = new Study();
        $this->_skillsModel = new MemberSkill();
        $this->_idMember = App_Utilities::getIdMember();
        $this->_memberModel = new Member();
        $this->_tagProfilModel = new App_Tag_Manage_TagProfil();
        $this->_companyModel = new Company();
        $this->_companyMemberModel = new CompanyMember();

        $this->_rvCompany = new ReferenceValue();
        $this->_rvSchool = new ReferenceValue();
        $this->_rvDomain = new ReferenceValue();
        $this->_rvSkill = new ReferenceValue();
    }

    /**
     * Display profile
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function indexAction() {
        $this->title = 'Display Profile';
        $this->profile();
        $row = $this->_memberModel->getMember($this->_idMember);
        $this->view->item = $row;
    }

    /**
     * Edit profile
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function editAction() {
        $this->title = 'Edit Profile';
        $this->profile();
    }

    //**************************** Manage Experience ********************************/
    /*     * ***************************************************************************** */

    /**
     * Update experience action, allow the user to update his experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function updatexperienceAction() {
        $this->title = 'update experience';
        $form = new ExperienceForm();

        $id = $this->_getParam('id');

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $value_id = $this->_rvCompany->searchCompany($form->getValue('name'));
                $data['rv_company_id'] = ($value_id) ? $value_id : $this->_rvCompany->addCompany($form->getValue('name'));
                $this->_experienceModel->updateExperience($data, $id);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            } else {
                $form->populate($formData);
            }
        } else {
            $form->populate($this->_experienceModel->getExperience($id));
        }
        $this->view->form = $form;
    }

    /**
     * Delete experience action, allow the user to delete his experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function deletexperienceAction() {
        $this->title = 'delete experience';
        $id = $this->_getParam('id');
        $$this->_experienceModel->deletExperience($id);
        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
    }

    /**
     * Add experience action, allow the user to add his experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function addexperienceAction() {
        $this->title = 'add experience';
        $form = new ExperienceForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $value_id = $this->_rvCompany->searchCompany($form->getValue('name'));
                $data['rv_company_id'] = ($value_id) ? $value_id : $this->_rvCompany->addCompany($form->getValue('name'));
                $this->_experienceModel->addExperience($data);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            }
        }
        $this->view->form = $form;
    }

    /*     * ********************* Study Manage ********************************** */
    /*     * ********************************************************************** */

    /* Update study action, allow the user to update his study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */

    public function updatestudyAction() {
        $this->title = 'update study';

        $form = new StudyForm();

        $id = $this->_getParam('id');

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();

                $value_id = $this->_rvSchool->searchStudy($form->getValue('school'));
                $data['rv_school_id'] = ($value_id) ? $value_id : $this->_rvSchool->addStudy($form->getValue('school'));

                $value_id = $this->_rvDomain->searchDomain($form->getValue('domain'));
                $data['rv_domain_id'] = ($value_id) ? $value_id : $this->_rvDomain->addDomain($form->getValue('domain'));

                $this->_studyModel->updateStudy($data, $id);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            } else {
                $form->populate($formData);
            }
        } else {
            $form->populate($this->_studyModel->getStudy($id));
        }

        $this->view->form = $form;
    }

    /**
     * Delete study action, allow the user to delete his study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function deletestudyAction() {
        $this->title = 'delete study';

        $id = $this->_getParam('id');

        $this->_studyModel->deleteStudy($id);
        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
    }

    /**
     * Add study action, allow the user to add his study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function addstudyAction() {
        $this->title = 'add study';
        $form = new StudyForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();

                $value_id = $this->_rvSchool->searchStudy($form->getValue('school'));
                $data['rv_school_id'] = ($value_id) ? $value_id : $this->_rvSchool->addStudy($form->getValue('school'));

                $value_id = $this->_rvDomain->searchDomain($form->getValue('domain'));
                $data['rv_domain_id'] = ($value_id) ? $value_id : $this->_rvDomain->addDomain($form->getValue('domain'));

                $this->_studyModel->addStudy($data);
                $this->_tagProfilModel->AddTagSchool($form->getValue('name'));

                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            }
        }
        $this->view->form = $form;
    }

    /*     * ****************** Skills Manage ********************************* */
    /*     * ********************************************************** */

    /**
     * Update skills action, allow the user to update the skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function updateskillsAction() { //not used
        $form = new SkillsForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id');
            $form->populate($this->_skillsModel->getSkills($id));
        }
        $this->view->form = $form;
    }

    /**
     * Delete skills action, allow the user to delete the skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function deleteskillsAction() {
        $this->title = 'delete skills';

        $id = $this->_getParam('id');
        $this->_skillsModel->deleteSkills($id);
        $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
    }

    /**
     * Add skills action, allow the user to add the skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function addskillsAction() {
        $this->title = 'add skills';
        $form = new SkillsForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $value_id = $this->_rvSkill->searchSkills($form->getValue('name'));
                $data['rv_skills_id'] = ($value_id) ? $value_id : $this->_rvSkill->addSkills($form->getValue('name'));
                $this->_skillsModel->addSkills($data);
                $this->_tagProfilModel->AddTagSkill($form->getValue('name'));

                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            }
        }
        $this->view->form = $form;
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     *  
     */
    public function displaysuggestmemberAction() {
        // $paginator = Zend_Paginator::factory(App_Tag_Kernel::run('MemberTag'));
        // $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->suggestMember = App_Tag_Kernel::run('MemberTag');
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function displaysuggestprojectAction() {
        $this->view->suggestProject = App_Tag_Kernel::run('ProjectTag');
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com  
     */
    public function addcompanyAction() {

        $this->title = 'update Company';
        $form = new CompanyForm();

        $id = $this->_companyMemberModel->getCompany(App_Utilities::getIdMember());

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $fullFilePath = $form->logo->receive();
                $pathparts = pathinfo($form->logo->getFileName());
                $data['logo'] = $pathparts['basename'];
                $this->_companyModel->updateCompany($data, $id);
                $this->_redirect($this->view->url(array('module' => 'frontend', 'controller' => 'profile', 'action' => 'index', 'id' => App_Utilities::getIdMember()), 'default', true));
            } else {
                $form->populate($formData);
            }
        } else {

            $form->populate($this->_companyModel->getCompany($id));
        }

        $this->view->form = $form;
    }

}