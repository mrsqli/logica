<?php

/**
 * 
 * Tag group Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class TagGroups extends App_Model {

    public $_primary = 'group_id';
    public $_name = 'tag_groups';
    protected $_order = 'priority';

    /** Find Group By priority
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function findGroupByPriority($group_tag_identifier) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,array('group_id'))
                ->where("is_active=1 and name_group in ('"
                        . implode(',', array_values($group_tag_identifier)) .
                        "')")
                ->order($this->_order . ' DESC');
        return $this->fetchAll($select);
    }

}