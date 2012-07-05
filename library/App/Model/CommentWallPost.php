<?php

/**
 * Model that manages the flags (controller names) for defining
 * the Flags in the application
 *
 * @package backoffice_models
 * @copyright company
 */
class CommentWallPost extends App_Model {

    protected $_primary = 'comment_id';
    protected $_name = 'comment_wall_post';

    public function getAllCommentByPostId($postId) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('C' => $this->_name),array('C.comment_id','C.comment_by_member_id','C.body','C.time_create'))
                ->join(array('W' => 'wall_post'), 'C.wall_post_id=W.wall_post_id',array())
                ->where('W.wall_post_id=' . $postId)
                ->order('C.time_create desc');

        return $this->fetchall($select);
    }

}