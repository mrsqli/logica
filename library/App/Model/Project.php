<?php

/**
 * Project Model
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Model
 * @category FrontOffice
 * @package model
 */
class Project extends App_Model {

    public $_primary = 'project_id';
    public $_name = 'projects';
    protected $_order = 'time_create';

  /**
     * get last project inscribed, they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function last_project($array_member) {

        $where = "P.project_id not in(" . implode(',', array_values($array_member['item_id'])) . ") ";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('P' => $this->_name), array('P.project_id'))
                ->limit($array_member['limit'])
                ->where($where)
                ->order($this->_order . ' DESC');

//        var_dump($select->__toString());

        return $this->fetchAll($select);
    }

   /**
     * get all projects of my contacts, (level 1), they must don't exsist in array_member
     * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
     * @param [array] 
     * @return [array]
     */
    public function project_my_contact($array_member) {

        $contact = new Contact();
        $id = App_Utilities::getIdMember();

        $where = "P.project_id not in(" . implode(',', array_values($array_member['item_id'])) . ") " .
                "and exists (select member_id from " . $contact->_name .
                " C where C.member_id=" . $id . " and P.member_id=C.friend_member_id)";

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('P' => $this->_name), array('P.project_id'))
                ->limit($array_member['limit'])
                ->where($where);
        return $this->fetchAll($select);
    }
 
    public function getProject($params){
//     $where="title ='".$projectName."'";
 $this->_iniConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
           $select = $this->select()->setIntegrityCheck(false)
                         ->from($this->_name);
              
       if(isset($params['nameProject']) && $params['nameProject']!=''){
           $select->where('projects.title = ? ',$params['nameProject']) ;
         }
         if(isset($params['dateBegin']) && $params['dateBegin']!=''){
          $dateBegin = new Zend_Date($params['dateBegin'],'dd/MM/yyyy');
           $paramConvertDateBegin = $dateBegin->toString('yyyy-dd-MM');
            $select->where('projects.time_create = ? ',$paramConvertDateBegin);
          }   
          if(isset($params['dateEnd']) && $params['dateEnd']!=''){
           $dateEnd = new Zend_Date($params['dateEnd'],'dd/MM/yyyy');
            $paramConvertDateEnd = $dateEnd->toString('yyyy-MM-dd');
             $select->where('projects.time_finish = ? ',$paramConvertDateEnd) ;
          }
          if(isset($params['budget']) && $params['budget']!=''){
            $select ->join('reference_values','reference_values.value_id=projects.rv_budget_id')
                  ->where('reference_values.name = ? ',$params['budget']) ;
           }
          if(isset($params['localisation']) && $params['localisation']!='0'){
           $select ->join('city','city.city_id=projects.city_id')
                  ->where('city.city_id = ? ',$params['localisation']) ;
           }
          if(isset($params['domaine']) && $params['domaine']!='0'){
           $select->where('projects.rv_domain_id = ? ',$params['domaine']) ;
         }
            if(isset($params['statut']) && $params['statut']!=''){
           $select->where('projects.rv_status_id = ?',$params['statut']) ;
         }
//          var_dump($select->__toString());die;
       return $this->fetchAll($select); 
        
       }
}