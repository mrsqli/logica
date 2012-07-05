<?php

/**
 * Allows the users to perform CRUD on URL operations 
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category BackOffice
 * @package Controller
 */
class RefvalueController extends App_Backoffice_Controller {

    /**
     * Overrides Zend_Controller_Action::init()
     *
     * @access public
     * @return void
     */
    public function init() {
        // init the parent
        parent::init();
    }

    /**
     * add url rewriting 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function indexAction() {
        $this->title = 'Value not Used.';
        $refvalue = new ReferenceValue();
       $this->view->refvalue = $refvalue->getRefValueNotUsed();
//        //  $this->view->paginator = $url->findAll($this->_getPage());
    }

    /**
     * add url rewriting 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function addAction() {

        $this->title = 'Add a new url.';
//        $form = new UrlRewritingForm();
//        $urlModel = new UrlAlias();
//
//        if ($this->getRequest()->isPost()) {
//            if ($form->isValid($this->getRequest()->getPost())) {
//                $urlModel->save($form->getValues());
//                $this->_helper->FlashMessenger(
//                        array(
//                            'msg-success' => 'The url was successfully added.',
//                        )
//                );
//                //Regenerate Flag and Flippers
//                App_FlagFlippers_Manager::save();
//
//                $this->_redirect('/url/');
//            }
//        }
//
//        $this->view->form = $form;
    }

    /**
     * edit url rewriting 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function editAction() {
        $this->title = 'Edit url';
//        $form = new UrlRewritingForm();
//        $urlModel = new UrlAlias();
//
//        if ($this->getRequest()->isPost()) {
//            if ($form->isValid($this->getRequest()->getPost())) {
//                $urlModel->save($form->getValues());
//                $this->_helper->FlashMessenger(
//                        array(
//                            'msg-success' => 'The url was successfully edited.',
//                        )
//                );
//                //Regenerate Flag and Flippers
//                App_FlagFlippers_Manager::save();
//
//                $this->_redirect('/url/');
//            }
//        } else {
//            $id = $this->_getParam('id');
//
//            //   var_dump($id);die;
//            if (!is_numeric($id)) {
//                $this->_helper->FlashMessenger(
//                        array(
//                            'msg-error' => 'The provided url_id is invalid.',
//                        )
//                );
//
//                $this->_redirect('/url/');
//            }
//            $row = $urlModel->findById($id);
//            if (empty($row)) {
//                $this->_helper->FlashMessenger(
//                        array(
//                            'msg-warning' => 'The requested url could not be found.',
//                        )
//                );
//                $this->_redirect('/url/');
//            }
//            $form->populate($row->toArray());
//            $this->view->item = $row;
//        }
//        $this->view->form = $form;
    }

    /**
     * delete url rewriting 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param 
     * @return 
     */
    public function deleteAction() {
        $this->title = 'Delete url';
//
//        $form = new DeleteForm();
//        $urlModel = new UrlAlias();
//
//        if ($this->getRequest()->isPost()) {
//            $urlModel->deleteById($this->_getParam('id'));
//            $this->_helper->FlashMessenger(
//                    array(
//                        'msg-success' => 'The url was successfully deleted.',
//                    )
//            );
//            //Regenerate Flag and Flippers
//            App_FlagFlippers_Manager::save();
//
//            $this->_redirect('/url/');
//        } else {
//            $id = $this->_getParam('id');
//            $row = $urlModel->findById($id);
//
//            if (empty($row)) {
//                $this->_helper->FlashMessenger(
//                        array(
//                            'msg-warning' => sprintf('We cannot find url with id %s', $id),
//                        )
//                );
//                $this->_redirect('/url/');
//            }
//
//            $form->populate($row->toArray());
//            $this->view->item = $row;
//        }
//
//        $this->view->form = $form;
   }

}