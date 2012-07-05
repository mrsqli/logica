<?php

/**
 * 
 * WallPost Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class WallPost extends App_Model {

    protected $_primary = 'wall_post_id';
    protected $_name = 'wall_post';
    protected $_order = 'time_create';
    protected $_accepte = 2;  // app.ini
    protected $_config;

    /**
     * Load config
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @return Zend_Config_Ini
     */
    protected function getConfig() {
        return new Zend_Config_Ini(APPLICATION_PATH . '/configs/hash_tagging_defaults.ini', 'defaults');
    }

    /* Common models instantiation
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     * @param <empty>
     * @return <empty>
     */

    public function init() {
        $this->_config = $this->getConfig();
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @return type 
     */
    public function getLastPostHasMyTags($idMember, $iswhere = FALSE, $last_timeCreate = 0) {
        $tagWall = new TagWall();
        $tagTerm = new TagTerm();
        $memberTag = new MemberTag();

        if ($iswhere == TRUE)
            $where = " and w.time_create>" . $last_timeCreate;

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->distinct()
                ->from(array('w' => $this->_name), array('w.wall_post_id', 'w.status', 'w.member_to_id', 'w.time_create'))
                ->join(array('TagW' => $tagWall->_name), 'TagW.' . $this->getPrimaryKey() . '=w.' . $this->getPrimaryKey(), array())
                ->join(array('TagT' => $tagTerm->_name), 'TagT.' . $tagTerm->_primary . '=TagW.' . $tagTerm->_primary, array())
                ->join(array('memberT' => $memberTag->_name), 'memberT.' . $tagTerm->_primary . '=TagT.' . $tagTerm->_primary, array())
                ->where("memberT.member_id=" . $idMember . $where)
                ->order('w.' . $this->_order . ' DESC')
                ->limit($this->_config->value->max_post);

        return $this->fetchAll($select);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $maxPost
     * @param type $arrayPost 
     */
    public function getLastPost($maxpost, $arrayPost, $iswhere = FALSE, $last_timeCreate = 0) {

        $tagWall = new TagWall();
        $tagTerm = new TagTerm();
        $memberTag = new MemberTag();

        $where = " w.wall_post_id not in(" . implode(',', array_values($arrayPost['item_id'])) . ") ";

        if ($iswhere == true)
            $where .= " and w.time_create>" . $last_timeCreate;

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->distinct()
                ->from(array('w' => $this->_name), array('w.wall_post_id', 'w.status', 'w.member_to_id', 'w.time_create'))
                ->join(array('TagW' => $tagWall->_name), 'TagW.' . $this->getPrimaryKey() . '=w.' . $this->getPrimaryKey(), array())
                ->join(array('TagT' => $tagTerm->_name), 'TagT.' . $tagTerm->_primary . '=TagW.' . $tagTerm->_primary, array())
                ->join(array('memberT' => $memberTag->_name), 'memberT.' . $tagTerm->_primary . '=TagT.' . $tagTerm->_primary, array())
                ->where($where)
                ->order('w.' . $this->_order . ' DESC')
                ->limit($maxpost);

        return $this->fetchAll($select);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @return type 
     */
    public function getMyContactPosts($idMember, $iswhere = FALSE, $last_timeCreate = 0) {

        $where = " exists (select C.friend_member_id from contact C where " .
                " ((w.member_to_id=C.friend_member_id and C.member_id=" . $idMember . ") " .
                " or (w.member_to_id=C.member_id and C.friend_member_id=" . $idMember . ")) " .
                ")";

        if ($iswhere == true)
            $where .= " and w.time_create>" . $last_timeCreate;

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('w' => $this->_name), array('w.wall_post_id','w.status', 'w.member_to_id', 'w.time_create'))
                ->where($where)
                ->order($this->_order . ' DESC')
                ->limit($this->_config->value->max_post);

        return $this->fetchAll($select);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @return type 
     */
    public function getMyWallPosts($idMember) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('w' => $this->_name), array('w.wall_post_id','w.status', 'w.member_to_id', 'w.time_create'))
                ->where('w.member_to_id=' . $idMember)
                ->order($this->_order . ' DESC')
                ->limit($this->_config->value->max_post);


        return $this->fetchAll($select);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @return type 
     */
    public function getPostsAllNetwork($idMember, $iswhere = false, $last_timeCreate = 0) {


        $numberPost = $this->_config->value->max_post;
        //$numberPostPertinent = $this->_config->value->max_post_pertinent;

        $postPertinent = $this->getLastPostHasMyTags($idMember, $iswhere, $last_timeCreate);

        $arrayIDPost = App_Utilities::convert_toArray($postPertinent, 'wall_post_id');
        $arrayPost = (!empty($arrayIDPost['item_id'])) ? $arrayIDPost : array('item_id' => array(0));
        $numberPostNormal = $numberPost - count($postPertinent);


        $postNormal = $this->getLastPost($numberPostNormal, $arrayPost, $iswhere, $last_timeCreate);

        $arrPosts = ($postPertinent->toArray() + $postNormal->toArray());
        $arrPostSorted = App_Utilities::aasort($arrPosts, 'time_create', 'DESC');

        return $arrPostSorted;
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @param type $lastWall
     * @return type 
     */
    public function checkOtherPostMyContact($idMember, $last_timeCreate) {
        return count($this->getMyContactPosts($idMember, true, $last_timeCreate));
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @param type $lastWall
     * @return type 
     */
    public function checkOtherPostAllContact($idMember, $last_timeCreate) {
        return count($this->getPostsAllNetwork($idMember, true, $last_timeCreate));
    }

}