<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Skills extends App_Model {

    public $_primary = 'skills_id';
    public $_name = 'skills';
    
  
  public function getSkills($param){
          $select = $this->select()->setIntegrityCheck(false)
                         ->from($this->_name)
                  ->where('category_id = ?',$param['category']);
          return $this->fetchAll($select);
         
  }
      public function putSkills($param){
          $select = $this->select()->setIntegrityCheck(false)
                         ->from($this->_name)
                  ->where('skills_id = ?',$param['skills']);
          return $this->fetchAll($select);
         
  }
    
    
}
?>
