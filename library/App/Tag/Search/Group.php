<?php

/**
 * Search Group (first)
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Search_Group {

    /**
     * search by group 
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $group_tag
     * @param type $model 
     */
    public static function Group_Tag(&$array_member, $group_tag, $model) {

        $tagGroup = new TagGroups();

        $listGroupTag = $tagGroup->findGroupByPriority($group_tag);

        foreach ($listGroupTag as $group) {

            if ($array_member['limit'] > 0) {

                App_Tag_Search_Criteria::Criteria_Tag($array_member, $group->group_id, $model);
            }
        }
    }

}