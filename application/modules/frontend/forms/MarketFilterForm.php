<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MarketFilterForm extends App_Frontend_Form{
    
    public function init(){
        
        $nameProject=new Zend_Form_Element_Text('nameProject');
        $nameProject->setLabel('nom Projet')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
         ;
        
        $dateBegin=new Zend_Form_Element_Text('dateBegin');
        $dateBegin->setLabel('à partir de :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')   
                 ;
        
        $dateEnd=new Zend_Form_Element_Text('dateEnd');
        $dateEnd->setLabel('jusqu\'à le :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
            ;
        
        $budget=new Zend_Form_Element_Text('budget');
        $budget->setLabel('Budget :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
              ;
        
        $localisation=new Zend_Form_Element_Select('localisation');
        $localisation->setLabel('Localisation :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
             ; 
        $cityModel=new City();
          $selectReferenceForCity = $cityModel->select()->setIntegrityCheck(false)
                     ->from('city') ;  
                 
          $localisation->addMultiOption(0, '-');
        foreach ($cityModel->fetchAll($selectReferenceForCity) as $row){
        $localisation->addMultiOption($row->city_id, $row->city_description);
          }
        
        $domaine=new Zend_Form_Element_Select('domaine');
        $domaine->setLabel('Domaine :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
              ; 
        $reference=new ReferenceValue();
        $selectReferenceForDomain = $reference->select()->setIntegrityCheck(false)
                     ->from('reference_values')   
                  ->where('reference_values.reference_Id=5');
          $domaine->addMultiOption(0, '-');
        foreach ($reference->fetchAll($selectReferenceForDomain) as $row){
        $domaine->addMultiOption($row->value_id, $row->name);
          }
          
        $statut=new Zend_Form_Element_Select('statut');
        $statut->setLabel('statut :')
                 ->setRequired(false)
                ->addFilter('StripTags')
               ->addFilter('StringTrim')
              ->addValidator('NotEmpty');
     $statut ->addMultiOptions(array(
                    '1' => 'encours',
                    '2' => 'valide',
                    '3'=>'suspendu' 
                        ));    
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
                array(
                    'label' => $this->t('Filter'),
                    'required' => true,
                )
        );
      
        
         $this->setCancelLink(false);
      
         $this->addElements(array($nameProject,$dateBegin,$dateEnd,$budget,$localisation,$domaine,$statut,$submit));
    }
    
}
?>
