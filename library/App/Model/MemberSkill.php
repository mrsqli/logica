<?php

/**
 * MemberSkills Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class MemberSkill extends App_Model {

    protected $_primary = 'id';
    protected $_name = 'member_skills';

    /**
     * get all skills for member connected
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getSkillsByMember($idMember) {

        $member = new Member();
        $skills = new ReferenceValue();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join($skills->_name, $skills->_primary . '=' . $this->_name . '.rv_skills_id',array('name'))
                ->where($member->_primary . '=' . $idMember);


        return $this->fetchAll($select);
    }

    /**
     * get row of skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getSkills($id) {

        $skills = new ReferenceValue();
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join($skills->_name, $skills->_primary . '=' . $this->_name . '.rv_skills_id',array('name'))
                ->where($this->getPrimaryKey() . '=' . $id)
                ->limit(1);

        $row = $this->fetchRow($select);
        return $row->toArray();
    }

    /**
     * delete skills by id
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return 
     */
    public function deleteSkills($id) {
        //var_dump($id);die;
        $this->delete($this->getPrimaryKey() . ' = ' . $id);
    }

    /**
     * add skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function addSkills($data) {

// test if : for not repeat the skills of member if he has it
        if (count($this->fetchRow('member_id=' . $data[member_id] . ' and rv_skills_id=' . $data[rv_skills_id])) == 0)
            $this->insert($data);
    }

    /**
     * update skills
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array,int] 
     * @return [the row of experience]
     */
    public function updateSkills($data, $id) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $id);
    }

}