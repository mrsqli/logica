<?php

/**
 * 
 * Tag Term Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class TagTerm extends App_Model {

    public $_primary = 'tag_id';
    public $_name = 'tag_terms';
    protected $_order = 'weight';

    /** Find Term by Weight
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function findTermsByWeight($criteria_id) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,array('tag_id'))
                ->where('criteria_id = ' . $criteria_id)
                ->order($this->_order . ' DESC');

        return $this->fetchAll($select);
    }

    /**
     * search tag , return id_tag if exsist else false
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int,string] 
     * @return [id_tag]
     */
    public function searchTag($ref, $name) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,array('tag_id'))
                ->where(" tag ='" . $name . "' and criteria_id=" . $ref)
                ->limit(1);

        $row = $this->fetchRow($select);
        return ($row != null) ? $row->tag_id : null;
    }

    /**
     * add tag , 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int,string] 
     * @return [tag_id]
     */
    public function addTag($ref, $name) {

        $data = array(
            'criteria_id' => $ref, //,
            'tag' => $name);

        return $this->insert($data);
    }

    
}