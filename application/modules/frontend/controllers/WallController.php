<?php

/**
 * Profil Controller To menage profil User
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Controller_Action
 * @category FrontOffice
 * @package Controller
 */
class WallController extends App_Frontend_Controller {

    protected $_wallModel;
    protected $_commentPostModel;
    protected $_idMember;
    protected $_tagSocialModel;
    protected $_session;
    protected $_sessionNameSpace = 'wallConfig';

    /* Common models instantiation
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        parent::init();
        $this->view->t = $this->t;

        $this->_wallModel = new WallPost();
        $this->_idMember = App_Utilities::getIdMember();
        $this->_session = new Zend_Session_Namespace($this->_sessionNameSpace);
        $this->_tagSocialModel = new App_Tag_Manage_TagSocial();
        $this->_commentPostModel = new CommentWallPost();
    }

    /**
     * display  contacts and other members posts
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function indexAction() {

        $this->title = 'Display wall';
        $filter = $this->_getParam('filter', null);
        $isAjax = $this->_getParam('isAjax', false);
        $notify = $this->_getParam('notify', false);

        $this->view->currFilter = $filter;

        if ($notify == 1) {
            if (is_null($filter) || $filter == 'all')
                $this->view->checkOtherWall = $this->_wallModel->checkOtherPostAllContact($this->_idMember, $this->_session->allcontact_lastTime);
            elseif ($filter == 'co')
                $this->view->checkOtherWall = $this->_wallModel->checkOtherPostMyContact($this->_idMember, $this->_session->mycontact_lastTime);


            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer('notify-ajax');
        } else {
            if ($isAjax == '1') {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer('index-ajax');
            }

            if (is_null($filter) || $filter == 'all') {
                $this->view->allContactWallPost = $tmp = $this->_wallModel->getPostsAllNetwork($this->_idMember);
                $this->_session->allcontact_lastTime = (!empty($tmp[0])) ? $tmp[0]['time_create'] : 0;
            } elseif ($filter == 'co') {

                $this->view->allContactWallPost = $tmp = $this->_wallModel->getMyContactPosts($this->_idMember);
                $this->_session->mycontact_lastTime = (!empty($tmp[0])) ? $tmp[0]['time_create'] : 0;
            } else
                $this->view->allContactWallPost = $this->_wallModel->getMyWallPosts($this->_idMember);
        }
    }

    /**
     * add new comment
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function addcommentAction() {

        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {
            $data = array(
                'wall_post_id' => $this->_getParam('post', null),
                'comment_by_member_id' => $this->_idMember,
                'body' => $this->_getParam('comment', null)
            );
            $idComment = $this->_commentPostModel->insert($data);


            $member = App_Utilities::getMember_Registry();
            $comment = array('image_url' => $member->image_url,
                'comment_id' => $idComment,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'time_create' => time(),
                'body' => $this->_getParam('comment', null)
            );
            $this->view->comment = $comment;
        }
    }

    /**
     * add new wall
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param <empty>
     * @return <empty>
     */
    public function addpostAction() {

        $this->title = 'add wall';
        if ($this->getRequest()->isPost()) {
            $wall_text = App_Utilities::_f($this->getRequest()->getPost('wall_comment'), true);
            $this->_tagSocialModel->AddTagWalls($wall_text);
            $this->_forward('index');
        }
    }

    public function deletecommentAction() {
        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {
            $comment = new CommentWallPost();
            $id = $this->_getParam('id', null);
            $comment->deleteById($id);
        }
    }

    /**
     * Add new comment to a given post
     *
     * @param <string> comment
     * @param <int> visitorId
     * @param <int> post
     *
     * @author Reda Menhouch, reda@fornetmaroc.com
     */
//    public function addcommentAction() {
//
//        $this->_helper->layout->disableLayout();
//        $commentText = $this->_request->getParam('comment');
//        $visitorId = $this->_request->getParam('visitorId');
//        $postId = $this->_request->getParam('post');
//
//        $commentDataItem = $this->_commentModel->create(array('clientId' => $visitorId, 'comment' => Default_Model_Utilities::filter_str($commentText), 'postId' => $postId));
//        if ($savedComment = $this->_commentModel->save($commentDataItem)) {
//            $this->view->addedComment = $this->_commentModel->getCommentData($this->_commentModel->find($savedComment));
//        }
//    }
}