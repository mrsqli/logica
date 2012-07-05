<?php

/**
 * Project Tag
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Suggest_ProjectTag extends App_Tag_Manage_Tags{

    static $_group_suggest = array('Project');

    /**
     * suggest
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
                0 => 0
            ),
            'index' => 1, // indice pour augmenter les performance de sauvegarde
            'limit' => $configInstance->value->max_project_suggestions //5
        );

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_ProjectTag::project_tag($array_member, self::$_group_suggest, $model);

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_ProjectTag::project_my_contact($array_member);

        if ($array_member['limit'] > 0)
            App_Tag_Suggest_ProjectTag::last_project($array_member);

       $suggest->saveSuggest($array_member, $model);
       
       return $array_member;
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $group_tag 
     */
    public static function project_tag(&$array_member, $group_tag, $model) {

        App_Tag_Search_Group::Group_Tag($array_member, $group_tag, $model);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member 
     */
    public static function project_my_contact(&$array_member) {

        $project = new Project();

        $array = $project->project_my_contact($array_member);
        App_Utilities::Convert_and_Concat($array_member, $array, 'project_id');
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member 
     */
    public static function last_project(&$array_member) {

        $project = new Project();

        $array = $project->last_project($array_member);
        App_Utilities::Convert_and_Concat($array_member, $array, 'project_id');
    }

}