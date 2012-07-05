<?php

/**
 * search Term
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
abstract class App_Tag_Search_Term {

    public static $_arrReferenceIds = array('ProjectTag' => 'project_id', 'MemberTag' => 'member_id');

    /**search by weight Term
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $criteria_id
     * @param type $group 
     */
    public static function Term_Tag(&$array_member, $criteria_id, $group) {

        $tagTerm = new TagTerm();
        $listTagTerm = $tagTerm->findTermsByWeight($criteria_id);

        self::compareTags($array_member, $listTagTerm, $group);
    }

    /**
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $array_member
     * @param type $listTagTerm 
     */
    public static function compareTags(&$array_member, $listTagTerm, $group) {

        $suggest_item = new $group();

        foreach ($listTagTerm as $tag) {

            if ($array_member['limit'] > 0) {

                $array = $suggest_item->fetchByTag($array_member, $tag->tag_id);
                App_Utilities::Convert_and_Concat($array_member, $array, self::$_arrReferenceIds[$group]);
            }
        }
    }

}