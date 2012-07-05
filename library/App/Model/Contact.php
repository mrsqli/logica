<?php

/**
 * Contact Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Contact extends App_Model {

    public $_primary = 'contact_id';
    public $_name = 'contact';

    /**
     * get all members of my contacts, (level 1), they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function contact_my_contact($array_member) {

        $where = "C.member_id in" .
                "(select C2.friend_member_id from " . $this->_name .
                " C2 where C2.member_id=" . $array_member['item_id'][0] . ")" .
                " and C.member_id not in(" . implode(',', array_values($array_member['item_id'])) . ") ";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('C' => $this->_name), array('C.friend_member_id'))
                ->limit($array_member['limit'])
                ->where($where);

        return $this->fetchAll($select);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $idMember
     * @return type 
     */
    public function getMy_Contact($idMember) {

        $member = new Member();
        $where = " exists (select C.member_id from Contact C where " .
                "(M.member_id=C.friend_member_id and C.member_id=" . $idMember . ") " .
                "or (M.member_id=C.member_id and C.friend_member_id=" . $idMember . ") " .
                ")";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('M' => $member->_name), array('M.member_id'))
                ->where($where);

        return $this->fetchAll($select);
    }

}