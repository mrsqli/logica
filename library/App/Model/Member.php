<?php

/**
 * Member Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Member extends App_Model {

    public $_primary = 'member_id';
    public $_name = 'members';
    protected $_order = 'time_create';

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $code
     * @return type 
     */
    public function codeIsValide($code) {

        return $this->fetchRow("hash_confirm = '" . $code . "'");
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $memberId 
     */
    public function validateInscription($memberId, $password) {

        $data = array(
            'validate' => 1,
            'id_member_type' => 2 //member non verifie
        );
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $memberId);
    }

    /** Update Hash Confirm 
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $memberId
     */
    public function saveTextConfirm($memberId, $hashconfirm) {


        $data = array(
            'hash_confirm' => $hashconfirm
        );

        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $memberId);
    }

    /**
     * get last member inscribed, they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function last_contact_inscribed($array_member) {

        $where = "M.member_id not in(" . implode(',', array_values($array_member['item_id'])) . ") ";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('M' => $this->_name), array('M.member_id'))
                ->limit($array_member['limit'])
                ->where($where)
                ->order($this->_order . ' DESC');

//        var_dump($select->__toString());

        return $this->fetchAll($select);
    }

    /**
     * update Member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function updateMember($data) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $data[member_id]);
    }

    /**
     * get member 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getMember($id) {

        $city = new ReferenceValue();

        $select = $this->select()
                ->limit(1)
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->joinleft($city->_name, $city->_primary . '=' . $this->_name . '.rv_city_id', array('name'))
                ->where($this->getPrimaryKey() . '=' . (int) $id);

        return $this->fetchRow($select);
    }

    /**
     * get id member from user connected
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [array]
     */
    public function getIdMemberFromUser() {
        $user = new BackofficeUser();
        $user_id = Zend_Auth::getInstance()->getIdentity()->id;
        if (is_numeric($user_id) && $user_id) {
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name, array($this->getPrimaryKey()))
                    ->join($user->_name, $user->_primary . '=' . $this->_name . '.backoffice_users_id', array())
                    ->where($user->_primary . '= ' . $user_id)
                    ->limit(1);

            return $this->fetchRow($select)->member_id;
        }
    }

    /**
     * update profil description
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return 
     */
    public function updateProfilDescription($data) {
        $this->update($data, $this->getPrimaryKey() . ' = ' . (int) $data[member_id]);
    }

    /**
     * add member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return 
     */
    public function addMember($userId, $parentId = 1) {

        $userModel = new BackofficeUser();
        $user = $userModel->fetchRow("id='" . $userId . "'");
        $data = array(
            'backoffice_users_id' => $user->id,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'email' => $user->email,
            'sponsor_parent_id' => $parentId,
            'id_member_type' => 1 //membre prospect
        );

        return $this->insert($data);
    }

    /**
     * calculate profile completion for member
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [int] 
     * @return [int]
     */
    public function calculateProfileCompletion($idMember, $regulate = 25) {

        $completion = 25;

        $experience = new Experience();
        $skills = new MemberSkill();
        $study = new Study();



        if (count($experience->getExperienceByMember($idMember)) > 0)
            $completion += $regulate;
        if (count($skills->getSkillsByMember($idMember)) > 0)
            $completion += $regulate;
        if (count($study->getStudyByMember($idMember)) > 0)
            $completion += $regulate;

        return $completion;
    }

}