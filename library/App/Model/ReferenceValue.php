<?php

/**
 * ReferenceValue Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class ReferenceValue extends App_Model {

    public $_primary = 'value_id';
    public $_name = 'reference_values';
    protected $_idCity = 1;
    protected $_idStudy = 2;
    protected $_idSkills = 3;
    protected $_idCompany = 4;
    protected $_idDomain = 5;

    /**
     * search reference , return id_reference if exsist else false
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int,string] 
     * @return [id_reference]
     */
    protected function search_reference($ref, $name) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,array($this->getPrimaryKey()))
                ->where(" name ='" . $name . "' and reference_Id=" . $ref)
                ->limit(1);
        $row = $this->fetchRow($select);
        return ($row != null) ? $row->value_id : null;
    }

    /**
     * add reference , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int,string] 
     * @return 
     */
    protected function add_reference($ref, $name) {

        $data = array(
            'reference_Id' => $ref, //,
            'name' => $name);

        return $this->insert($data);
    }

    /**
     * return variable count used , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [] 
     * @return 
     */
    protected function search_count_reference($ref) {
        
    }

    //***************************************************************************************** Skills/
    /**
     * search skills , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_skills]
     */
    public function searchSkills($name) {
        return $this->search_reference($this->_idSkills, $name);
    }

    /**
     * add Skills , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_skills]
     */
    public function addSkills($name) {
        return $this->add_reference($this->_idSkills, $name);
    }

    //***************************************************************************************** Study/
    /**
     * search Study , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_study]
     */
    public function searchStudy($name) {
        return $this->search_reference($this->_idStudy, $name);
    }

    /**
     * add study , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_study]
     */
    public function addStudy($name) {
        return $this->add_reference($this->_idStudy, $name);
    }

    //***************************************************************************************** City/
    /**
     * search City , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function searchCity($name) {
        return $this->search_reference($this->_idCity, $name);
    }

    /**
     * add City , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function addCity($name) {
        return $this->add_reference($this->_idCity, $name);
    }

//***************************************************************************************** Company/
    /**
     * search Company 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function searchCompany($name) {
        return $this->search_reference($this->_idCompany, $name);
    }

    /**
     * add Company 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function addCompany($name) {
        return $this->add_reference($this->_idCompany, $name);
    }

//***************************************************************************************** Domain/
    /**
     * search Domain 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function searchDomain($name) {
        return $this->search_reference($this->_idDomain, $name);
    }

    /**
     * add City , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [string] 
     * @return [id_City]
     */
    public function addDomain($name) {
        return $this->add_reference($this->_idDomain, $name);
    }

    /**
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getRefValueNotUsed() {

        $member = new Member();
        $ref = new Reference();

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->join($ref->_name, $ref->_name . '.' . $ref->_primary . '=' . $this->_name . '.reference_id')
                ->where('count_used = 0');


        return $this->fetchAll($select);
    }

}