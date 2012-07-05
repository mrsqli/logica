<?php

/**
 * Form that allows the user to add/update his skills
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class SponsorForm extends App_Frontend_Form {

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


        $idMember = App_Utilities::getIdMember();

        $member_id = new Zend_Form_Element_Hidden('member_id');
        $member_id->addFilter('Int')
                ->setValue($idMember);
        $this->addElement($member_id);


        $firstName = new Zend_Form_Element_Text('fistName_son');
        $firstName->setOptions(
                array(
                    'value' => $this->t('First name'),
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

        $lastName = new Zend_Form_Element_Text('lastName_son');
        $lastName->setOptions(
                array(
                    'value' => $this->t('Last name'),
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
        $this->addElement($lastName);

        $email = new Zend_Form_Element_Text('email_son');
        $email->setOptions(
                array(
                    'value' => $this->t('email'),
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
        $this->addElement($email);

        $submit = new Zend_Form_Element_Submit('submit_sponsor');
        $submit->setOptions(
                array(
                    'label' => $this->t('Envoyer mon invitation'),
                    'required' => true,
                )
        );
        $this->addElement($submit);
    }

}