<?php

/**
 * Suggest Member Tag
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Suggest_MemberTag extends App_Tag_Manage_Tags {

    static $_group_suggest = array('Profil');

    /**
     * 
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @uses absttract_class
     * @category FrontOffice
     * @package library/app
     */
    public static function suggest($model) {

        $suggest = new MemberSuggestions();
        $configInstance = self::getConfig();
        $array_member = array(
            'item_id' => array(
                0 => App_Utilities::getIdMember(),
            ),
            'index' => 1, // indice pour augmenter les performance de sauvegarde
            'limit' => $configInstance->value->max_member_suggestions //30
        );

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_MemberTag::profil_tag($array_member, self::$_group_suggest, $model);

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_MemberTag::profil_visit($array_member, self::$_group_suggest);

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_MemberTag::contact_my_contact($array_member, self::$_group_suggest);

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_MemberTag::last_contact_registered($array_member, self::$_group_suggest);


        $suggest->saveSuggest($array_member, $model);

        return $array_member;
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $group_tag 
     */
    public static function profil_tag(&$array_member, $group_tag, $model) {

        App_Tag_Search_Group::Group_Tag($array_member, $group_tag, $model);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member 
     * return 
     */
    public static function profil_visit(&$array_member) {

        $memberVisit = new MemberVisit();

        $array = $memberVisit->profil_visit($array_member);
        App_Utilities::Convert_and_Concat($array_member, $array, 'visit_profil_id');
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member 
     * return 
     */
    public static function contact_my_contact(&$array_member) {

        $contact = new Contact();

        $array = $contact->contact_my_contact($array_member);
        App_Utilities::Convert_and_Concat($array_member, $array, 'friend_member_id');
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member 
     * return 
     */
    public static function last_contact_registered(&$array_member) {

        $member = new Member();

        $array = $member->last_contact_inscribed($array_member);
        App_Utilities::Convert_and_Concat($array_member, $array, 'member_id');
    }

}