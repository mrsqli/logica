<?php

/**
 * Form that allows the user to add/update his skills
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class SearchForm extends App_Frontend_Form {

    /**
     * Overrides init() in Zend_Form
     * 
     * @access public
     * @return void
     */
    public function init() {
        // init the parent
        parent::init();

        // set the form's method





        $firstName = new Zend_Form_Element_Text('search');
        $firstName->setOptions(
                array(
                    'value' => $this->t('Search'),
                    'required' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
                    ),
                )
        );
        $this->addElement($firstName);
        
        $status = new Zend_Form_Element_Radio('status');
        $status->setOptions(
                array(
                    'label' => $this->t('Type'),
                    'required' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    //  $groupsInArrayValidator
                    ),
                    'validators' => array(
                        'NotEmpty',
                    ),
                    'multiOptions' => array('Reseau' => 'Réseau', 'Mes Contacts' => 'Mes Contacts'),
                )
        );
        $this->addElement($status);
        $submit = new Zend_Form_Element_Submit('submit_search');
        $submit->setOptions(
                array(
                    'label' => $this->t('Search'),
                    'required' => true,
                )
        );
        $this->addElement($submit);
    }

}