<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Category extends App_Model {

    public $_primary = 'category_id';
    public $_name = 'category';
    
   public function getCategory(){
        
       $select = $this->select()->setIntegrityCheck(false)
                     ->from($this->_name);
        return $this->fetchAll($select);
         
  }
  
    
    
    
}
?>
