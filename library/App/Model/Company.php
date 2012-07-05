<?php

/**
 * 
 * Xompany Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Company extends App_Model {

    protected $_primary = 'company_id';
    protected $_name = 'company';

    public function addCompany($name) {

        $data = array('name' => $name);
        return $this->insert($data);
    }

    public function updateCompany($data, $id) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $id);
    }

    public function getCompany($id) {
        return $this->fetchRow('company_id=' . $id)->toArray();
    }

    public function getCompanyFromIdMember($idMember) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->where($this->getPrimaryKey() . '=' . $id)
                ->limit(1);

        $row = $this->fetchRow($select);
        return $row->toArray();
    }

}