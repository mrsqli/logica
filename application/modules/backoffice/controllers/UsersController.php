<?php

/**
 * Allows the users to perform CRUD operations on other users
 *
 * @category backoffice
 * @package backoffice_controllers
 * @copyright company
 */
class UsersController extends App_Backoffice_Controller {

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
     * Allows users to see all other users that are registered in
     * the application
     *
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->title = '';

        $userModel = new BackofficeUser();
        $this->view->paginator = $userModel->findAll($this->_getPage());
    }

    /**
     * Allows users to add new users in the application
     * (should be reserved for administrators)
     *
     * @access public
     * @return void
     */
    public function addAction() {
        $this->title = 'Add a new user';

        $form = new UserAddForm();
        $userModel = new BackofficeUser();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $userModel->save($form->getValues());
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The user was successfully added',
                        )
                );

                App_FlagFlippers_Manager::save();

                $this->_redirect('/users/');
            }
        }

        $this->view->form = $form;
    }

    /**
     * Allows users to edit another users' data
     * (should be reserved for administrators)
     *
     * @access public
     * @return void
     */
    public function editAction() {
        $this->title = 'Edit this user';

        $form = new UserForm();
        $userModel = new BackofficeUser();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                
                
                $userModel->save($form->getValues());
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The user was successfully updated',
                        )
                );

                App_FlagFlippers_Manager::save();

                $this->_redirect('/users/');
            }
        } else {
            $id = $this->_getParam('id');

            if (!is_numeric($id)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'The user id you provided is invalid',
                        )
                );

                $this->_redirect('/users/');
            }

            if ($id == 1) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'It is forbidden to mess with the admin account in this release.',
                        )
                );

                $this->_redirect('/users/');
            }

            $row = $userModel->findById($id);

            if (empty($row)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'The requested user could not be found',
                        )
                );

                $this->_redirect('/users/');
            }

            $data = $row->toArray();
            $data['groups'] = $row->groupIds;
            $form->populate($data);

            $this->view->item = $row;
        }

        $this->view->form = $form;
    }

    /**
     * Allows users to see other users' profiles
     *
     * @access public
     * @return void
     */
    public function viewAction() {
        $this->title = 'User details';

        $userModel = new BackofficeUser();
        $id = $this->_getParam('id');

        if (!is_numeric($id)) {
            $this->_helper->FlashMessenger(
                    array(
                        'error' => 'The user id you provided is invalid',
                    )
            );

            $this->_redirect('/users/');
        }

        $row = $userModel->findById($id);

        if (empty($row)) {
            $this->_helper->FlashMessenger(
                    array(
                        'error' => 'The user was successfully added',
                    )
            );

            $this->_redirect('/users/');
        }

        $this->view->backlink = Zend_Controller_Front::getInstance()->getBaseUrl() . '/users/';
        $this->view->item = $row;
    }

    /**
     * Allows users to logically delete other users
     * (should be reserved for administrators)
     *
     * @access public
     * @return void
     */
    public function deleteAction() {
        $this->title = 'Delete this user';

        $form = new DeleteForm();
        $userModel = new BackofficeUser();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $userModel->deleteById($form->getValue('id'));
                $this->_helper->FlashMessenger(
                        array(
                            'msg-success' => 'The item was successfully deleted.',
                        )
                );

                App_FlagFlippers_Manager::save();

                $this->_redirect('/users/');
            }
        } else {
            $id = $this->_getParam('id');

            if (!is_numeric($id)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'The id you provided is invalid.',
                        )
                );

                $this->_redirect('/users/');
            }

            if ($id == 1) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'It is forbidden to mess with the admin account in this release.',
                        )
                );

                $this->_redirect('/users/');
            }

            $row = $userModel->findById($id);

            if (empty($row)) {
                $this->_helper->FlashMessenger(
                        array(
                            'msg-error' => 'The requested item cannot be found.',
                        )
                );

                $this->_redirect('/users/');
            }

            $this->view->item = $row;
            $form->populate($row->toArray());
        }

        $this->view->form = $form;
    }

}