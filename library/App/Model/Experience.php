<?php

/**
 * Experience Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Experience extends App_Model {

    public $_primary = 'experience_id';
    public $_name = 'experience';

    /**
     * get all experience for member connected
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getExperienceByMember($idMember) {

        $member = new Member();
        $company = new ReferenceValue();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join($company->_name, $company->_primary . '=' . $this->_name . '.rv_company_id', array('name') )
                ->where($member->_primary . '=' . $idMember);


        return $this->fetchAll($select);
    }

    /**
     * get row of experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getExperience($id) {

        $company = new ReferenceValue();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join($company->_name, $company->_primary . '=' . $this->_name . '.rv_company_id',  array('name') )
                ->where($this->getPrimaryKey() . '=' . $id)
                ->limit(1);

        $row = $this->fetchRow($select);
        return $row->toArray();
    }

    /**
     * delete experience by id
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return 
     */
    public function deletExperience($id) {
        $this->delete($this->getPrimaryKey() . ' = ' . (int) $id);
    }

    /**
     * add experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function addExperience($data) {

        $this->insert($data);
    }

    /**
     * update experience
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array,int] 
     * @return [the row of experience]
     */
    public function updateExperience($data, $id) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $id);
    }

}