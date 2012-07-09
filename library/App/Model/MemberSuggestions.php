<?php

/**
 * Memer Suggestions Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class MemberSuggestions extends App_Model {

    public $_primary = 'suggest_id';
    public $_name = 'member_suggestions';
    protected $_group_profil = 'Profil';
    protected $_group_project = 'Project';

    /**
     * save suggest, param $group has 2  values for the moment (ProjectTag,MemberTag)
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array,$array] 
     * @return []
     */
    public function saveSuggest(&$array, $group) {

        $size = count($array['item_id']);
        $data = array();

        $id = ($group == 'MemberTag') ? $array['item_id'][0] : App_Utilities::getIdMember();

        for ($i = $array['index']; $i < $size; $i++) {
            $data['member_id'] = $id;
            $data['item_id'] = $array['item_id'][$i];
            $data['group_name'] = $group;
            if (!$this->getRow($data))
                $this->save($data);
        }
        $array['index'] = $i;
    }

    /**
     * get row in membre suggestion
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function getRow($array) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->where('member_id = ' . $array['member_id'] . ' and item_id =' . $array['item_id']);

        return $this->fetchRow($select);
    }

    /**
     * save suggest, param $group has 2  values for the moment (ProjectTag,MemberTag)
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array,$array,string,$group] 
     * @return []
     */
    public function Add_Suggest(&$array_member, $array, $key, $group) {

        $suggest = new MemberSuggestions();

        $array = App_Utilities::convert_toArray($array, $key);
        App_Utilities::Concat_Result($array_member, $array);
        $array_member['limit']-=count($array['item_id']);

        switch ($group) {
            case 'ProjectTag': $suggest->saveSuggest($array_member, $this->_group_project);
                break;
            case 'MemberTag':$suggest->saveSuggest($array_member, $this->_group_profil);
                break;
        }
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function getSuggestMember($idMember) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->where('member_id = ' . $idMember." and group_name='MemberTag'");

        return $this->fetchall($select)->toArray();
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com 
     */
    public function deleteSuggestMember($id) {

        $this->delete('member_id=' . $id);
    }

}