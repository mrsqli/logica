<?php

/**
 * 
 * Study Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Study extends App_Model {

    protected $_primary = 'study_id';
    protected $_name = 'study';

    /**
     * get all study for member conncted
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [the rows of study]
     */
    public function getStudyByMember($idMember) {


        $member = new Member();
        $school = new ReferenceValue();
        $domain = new ReferenceValue();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name )
                ->join(array('S' => $school->_name), 'S.' . $school->_primary . '=' . $this->_name . '.rv_school_id',array('school' => 'S.name'))
                ->join(array('D' => $domain->_name), 'D.' . $domain->_primary . '=' . $this->_name . '.rv_domain_id',array('domain' => 'D.name'))
                ->where($member->_primary . '=' . $idMember);

        return $this->fetchAll($select);
    }

    /**
     * get row of study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [the row of study]
     */
    public function getStudy($id) {

        $school = new ReferenceValue();
        $domain = new ReferenceValue();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join(array('S' => $school->_name), 'S.' . $school->_primary . '=' . $this->_name . '.rv_school_id',array('school' => 'S.name'))
                ->join(array('D' => $domain->_name), 'D.' . $domain->_primary . '=' . $this->_name . '.rv_domain_id',array('domain' => 'D.name'))
                ->where($this->getPrimaryKey() . '=' . $id)
                ->limit(1);


        $row = $this->fetchRow($select);
        return $row->toArray();
    }

    /**
     * delete study by id
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return 
     */
    public function deleteStudy($id) {
        $this->delete($this->getPrimaryKey() . ' = ' . (int) $id);
    }

    /**
     * add study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function addStudy($data) {
        return $this->insert($data);
    }

    /**
     * update study
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array,int] 
     * @return [the row of experience]
     */
    public function updateStudy($data, $id) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $id);
    }

}