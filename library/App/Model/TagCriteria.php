<?php

/**
 * 
 * Tag Criteria Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class TagCriteria extends App_Model {

    protected $_primary = 'criteria_id';
    protected $_name = 'tag_criteria';
    protected $_order = 'coefficient';

    
     /** find Criteria By Coefficient
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function findCriteriaByCoefficient($group_id) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,array('criteria_id'))
                ->where('group_id = ' . $group_id . ' and is_active=1')
                ->order($this->_order . ' DESC');

        return $this->fetchAll($select);
    }

}