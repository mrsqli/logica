<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Business Controller for projects
 */

class BusinessController extends App_Frontend_Controller {

    public function marketAction() {

        $this->_helper->layout()->setLayout('business');
        $form = new MarketFilterForm();
        $project = new Project();
        $select = $project->select()->setIntegrityCheck(false)
                         ->from('projects');
        $paginator = Zend_Paginator::factory($project->fetchAll($select));
        $paginator->setCurrentPageNumber($this->_request->getParam('page',1))
                 ->setItemCountPerPage(1);
        $this->view->projects = $paginator;
        $this->view->form = $form;
    }

    public function filterAction() {
        
        $this->_helper->layout('business')->disableLayout();
        $projectModel = new Project();
        $projectReturn = $projectModel->getProject($this->_request->getParams());
        $paginator = Zend_Paginator::factory($projectReturn);
        $paginator->setCurrentPageNumber($this->_request->getParam('page',1))
                 ->setItemCountPerPage(1);
        $this->view->projects = $paginator;
    }
    
    public function projectcreationAction(){
        
          $this->_helper->layout()->setLayout('business');
          $categoryProject=new Category();
          $selectCategories=$categoryProject->getCategory();
          $this->view->categoryProject = $selectCategories;
    }
    public function findskillsAction(){
        
          $this->_helper->layout()->disableLayout();
          $skills=new Skills();
          $selectSkills=$skills->getSkills($this->_request->getParams());
          $this->view->skillsforcategory=$selectSkills;
    }
    public function putskillsAction(){
         $this->_helper->layout()->disableLayout();
          $skills=new Skills();
          $selectSkills=$skills->putSkills($this->_request->getParams());
          $this->view->putskills=$selectSkills;
    }

}

?>
