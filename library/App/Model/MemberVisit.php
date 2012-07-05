<?php

/**
 * 
 * Member Visit Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class MemberVisit extends App_Model {

    protected $_primary = 'member_visit_id';
    protected $_name = 'member_visit';

    public function profil_visit($array_member) {

        $where = "M.visit_profil_id not in(" . implode(',', array_values($array_member['item_id'])) . ") " .
                "and M.owner_profil_id=" . $array_member['item_id'][0];

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('M' => $this->_name), array('M.visit_profil_id'))
                ->limit($array_member['limit'])
                ->where($where);

        return $this->fetchAll($select);
    }

}