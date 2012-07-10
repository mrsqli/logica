<?php

/**
 * Model that manages the flags (controller names) for defining
 * the Flags in the application
 *
 * @package backoffice_models
 * @copyright company
 */
class CompanyMember extends App_Model {

    protected $_primary = 'id';
    protected $_name = 'company_members';

    public function addMemberInCompany($idCompany, $idMember, $status) {
        $data = array(
            'company_id' => $idCompany,
            'member_id' => $idMember,
            'function' => $status
        );

        return $this->insert($data);
    }

    public function getCompany($idMember) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name, array('company_id'))
                ->where('member_id=' . $idMember)
                ->limit(1);

        $row = $this->fetchRow($select);
        return $row->company_id;
    }

    public function getCompanyByMember($idMember) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->where('member_id=' . $idMember)
                ->limit(1);

        return $this->fetchrow($select);
    }

}