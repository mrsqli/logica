<?php

/**
 * App hashtag, this class is for system hash tag
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
class App_Tag_Manage_TagProfil extends App_Tag_Manage_Tags {

   // protected
    /**
     * app.ini
     * @var type 
     */
    protected $_idSchool = 1;
    protected $_idSkill = 2;
    protected $_idCity = 3;
    protected $_idCompany = 4;
    protected $_idWall = 7;
    protected $_idDomain = 8;

    /*    add tag school
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $name
     * return
     */

    public function init() {
        
    }

    public function AddTagSchool($schoolTag) {

        $data = Array();
        $memberHashTag = new MemberTag();
        $school = new TagTerm();

        $value_id = $school->searchTag($this->_idSchool, $schoolTag);
        $data['member_id'] = App_Utilities::getIdMember();
        $data['tag_id'] = ($value_id) ? $value_id : $school->addTag($this->_idSchool, $schoolTag);
        $memberHashTag->insert($data);
    }

    /** add Tag skill
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $name
     * return 
     */
    public function AddTagSkill($skillTag) {

        $data = Array();
        $memberHashTag = new MemberTag();
        $skill = new TagTerm();

        $value_id = $skill->searchTag($this->_idSkill, $skillTag);

        $data['member_id'] = App_Utilities::getIdMember();
        $data['tag_id'] = ($value_id) ? $value_id : $skill->addTag($this->_idSkill, $skillTag);

        $memberHashTag->insert($data);
    }

    /** add Tag city
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $cityTag
     * return 
     */
    public function AddTagCity($cityTag) {

        $data = Array();
        $memberHashTag = new MemberTag();
        $city = new TagTerm();

        $value_id = $city->searchTag($this->_idCity, $cityTag);

        $data['member_id'] = App_Utilities::getIdMember();
        $data['tag_id'] = ($value_id) ? $value_id : $city->addTag($this->_idCity, $cityTag);

        $memberHashTag->insert($data);
    }

    /** add Tag company
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $name
     * return 
     */
    public function AddTagCompany($companyTag) {

        $data = Array();
        $memberHashTag = new MemberTag();
        $company = new TagTerm();

        $value_id = $company->searchTag($this->_idCompany, $companyTag);

        $data['member_id'] = App_Utilities::getIdMember();
        $data['tag_id'] = ($value_id) ? $value_id : $company->addTag($this->_idCompany, $companyTag);

        $memberHashTag->insert($data);
    }

    /** add Tag Domain
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $name
     * return 
     */
    public function AddTagDomain($domainTag) {

        $data = Array();
        $memberHashTag = new MemberTag();
        $domain = new TagTerm();

        $value_id = $domain->searchTag($this->_idCompany, $domainTag);

        $data['member_id'] = App_Utilities::getIdMember();
        $data['tag_id'] = ($value_id) ? $value_id : $domain->addTag($this->_idCompany, $domainTag);

        $memberHashTag->insert($data);
    }

}