<?php

/**
 * App hashtag, this class is for system hash tag
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses absttract_class
 * @category FrontOffice
 * @package library/app
 */
class App_Tag_Manage_TagSocial extends App_Tag_Manage_Tags {

    protected $_idWall = 9;

    /*    add tag school
     * @author : ELGUENNUNI Sohaib, s.elguennuni@gmail.com
     * @param type $name
     * return
     */

    public function AddTagWalls($textWall, $wallPostID) {

        $addwall = array('member_to_id' => App_Utilities::getIdMember(),
            'member_by_id' => App_Utilities::getIdMember(),
            'status' => $textWall);

        $addWallTag = array('tag_id'=>'', 'wall_post_id'=>'');
        $addMemberTag = Array('tag_id'=>0, 'member_id' =>(int) App_Utilities::getIdMember());



        $wall_post = new WallPost();
        $tagWall = new TagWall();
        $tagTerm = new TagTerm();
        $memberHashTag = new MemberTag();

        $wallPostID = $wall_post->insert($addwall);
        $tagsWall = self::fetch_post_tags($textWall);


    
        foreach ($tagsWall as $key=>$tag) {

            $value_id = $tagTerm->searchTag($this->_idWall, $tag);

            $addWallTag['tag_id'] = $addMemberTag['tag_id'] = ($value_id) ? $value_id : $tagTerm->addTag($this->_idWall, $tag);
            
            $memberHashTag->insertTagMember($addMemberTag);

            $addWallTag['wall_post_id'] = $wallPostID;
            $tagWall->insert($addWallTag);
        }
    }

}