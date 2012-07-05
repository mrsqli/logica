<?php

/**
 * Search Criteria (second)
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Search_Criteria {

    /** search by criteria
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $group_id
     * @param type $group_tag 
     */
    public static function Criteria_Tag(&$array_member, $group_id, $group_tag) {

        $tagCriteria = new TagCriteria();

        $listCriteriaTag = $tagCriteria->findCriteriaByCoefficient($group_id);

        foreach ($listCriteriaTag as $criteria) {

            if ($array_member['limit'] > 0) {
                App_Tag_Search_Term::Term_Tag($array_member, $criteria->criteria_id, $group_tag);
            }
        }
    }

}