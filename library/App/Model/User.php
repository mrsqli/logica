<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Administrateur
 */
class User extends BackofficeUser {
    //put your code here
    
    
    public function __construct() {
parent::__construct();
}
    
// public function findById($userId){
//        $user = parent::findById($userId);
//        if(!empty($user)){
//            $user->groups = $user->findManyToManyRowset('Group', 'User');
//            
//            foreach($user->groups as $group){
//                $user->groupNames[] = $group->name;
//                $user->groupIds[] = $group->id;
//            }
//        }
//        
//        return $user;
//    }
//    
}

?>
