<?php

/**
 * Allows the users to perform CRUD operations on privileges
 *
 * @category backoffice
 * @package backoffice_controllers
 * @copyright company
 */
class PrivilegesController extends App_Backoffice_Controller {

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
     * Allows the user to view all the permissions registered
     * in the application
     *
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->title = '';

        $privilegeModel = new Privilege();
        $this->view->paginator = $privilegeModel->findAll($this->_getPage());
    }

    /**
     * Allows the user to add another privilege in the application
     *
     * @access public
     * @return void
     */
    public function addAction() {
        $this->title = 'Add a new privilege.';

        $form = new PrivilegeForm();
        $privilegeModel = new Privilege();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $privilegeModel->save($form->getValues());
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The privilege was successfully added.',
                        )
                );

                //Regenerate Flag and Flippers
                App_FlagFlippers_Manager::save();

                $this->_redirect('/privileges/');
            }
        }

        $this->view->form = $form;
    }

    /**
     * Edits an existing privilege
     *
     * @access public
     * @return void
     */
    public function editAction() {
        $this->title = 'Edit privilege';

        $form = new PrivilegeForm();
        $privilegeModel = new Privilege();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $privilegeModel->save($form->getValues());
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The privilege was successfully edited.',
                        )
                );

                //Regenerate Flag and Flippers
                App_FlagFlippers_Manager::save();

                $this->_redirect('/privileges/');
            }
        } else {
            $id = $this->_getParam('id');

            if (!is_numeric($id)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'The provided privilege_id is invalid.',
                        )
                );

                $this->_redirect('/privileges/');
            }

            $row = $privilegeModel->findById($id);

            if (empty($row)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-warning' => 'The requested privilege could not be found.',
                        )
                );

                $this->_redirect('/privileges/');
            }

            $form->populate($row->toArray());
            $this->view->item = $row;
        }

        $this->view->form = $form;
    }

    /**
     * Allows the user to delete an existing privilege. All the flippers related to
     * this privilege will be removed
     *
     * @access public
     * @return void
     */
    public function deleteAction() {
        $this->title = 'Delete privilege';

        $form = new DeleteForm();
        $privilegeModel = new Privilege();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $privilegeModel->deleteById($form->getValue('id'));
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The privilege was successfully deleted.',
                        )
                );

                //Regenerate Flag and Flippers
                App_FlagFlippers_Manager::save();

                $this->_redirect('/privileges/');
            }
        } else {
            $id = $this->_getParam('id');
            $row = $privilegeModel->findById($id);

            if (empty($row)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-warning' => sprintf('We cannot find privilege with id %s', $id),
                        )
                );
                $this->_redirect('/privileges/');
            }

            $form->populate($row->toArray());
            $this->view->item = $row;
        }

        $this->view->form = $form;
    }

   

}