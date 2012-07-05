<?php

/**
 * Project Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Project extends App_Model {

    public $_primary = 'project_id';
    public $_name = 'projects';
    protected $_order = 'time_create';

  /**
     * get last project inscribed, they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function last_project($array_member) {

        $where = "P.project_id not in(" . implode(',', array_values($array_member['item_id'])) . ") ";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('P' => $this->_name), array('P.project_id'))
                ->limit($array_member['limit'])
                ->where($where)
                ->order($this->_order . ' DESC');

//        var_dump($select->__toString());

        return $this->fetchAll($select);
    }

   /**
     * get all projects of my contacts, (level 1), they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function project_my_contact($array_member) {

        $contact = new Contact();
        $id = App_Utilities::getIdMember();

        $where = "P.project_id not in(" . implode(',', array_values($array_member['item_id'])) . ") " .
                "and exists (select member_id from " . $contact->_name .
                " C where C.member_id=" . $id . " and P.member_id=C.friend_member_id)";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('P' => $this->_name), array('P.project_id'))
                ->limit($array_member['limit'])
                ->where($where);

        return $this->fetchAll($select);
    }

}