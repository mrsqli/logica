<?php

/**
 * Memer Tag Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class MemberTag extends App_Model {

    public $_primary = 'member_tag_id';
    public $_name = 'member_tag';

    /**
     * Gets member array for selected users and tag_id for single term
     * Returns member suggestions for this tag
     * 
     * @param type $array_member
     * @param type $tag_id
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @return [array]
     */
    public function fetchByTag($array_member, $tag_id) {

        $where = "exists (select member_id from member_tag Me where Me.member_id=" . $array_member['item_id'][0] .
                " and Me.tag_id=" . $tag_id . ") 
                 and  M.tag_id=" . $tag_id
                . " and M.member_id not in(" . implode(',', array_values($array_member['item_id'])) . ") ";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('M' => $this->_name), array('M.member_id'))
                ->limit($array_member['limit'])
                ->where($where);

        return $this->fetchAll($select);
    }

    /**
     * insertTag
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function insertTagMember($data) {

        if (count($this->fetchRow('member_id=' . $data['member_id'] . ' and tag_id=' . $data['tag_id'])) == 0)
            $this->insert($data);

    }

}