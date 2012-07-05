<?php

/**
 * Form that allows the user to update his profil
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Zend_Form
 * @category FrontOffice
 * @package form
 */
class ProfileForm extends App_Frontend_Form {

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
        $this->setMethod('post');

//        $username = new Zend_Form_Element_Text('username');
//        $username->setOptions(
//                array(
//                    'label' => $this->t('Username'),
//                    'required' => true,
//                    'filters' => array(
//                        'StringTrim',
//                        'StripTags',
//                    ),
//                    'validators' => array(
//                        'NotEmpty',
//                    ),
//                    'readonly' => 'readonly',
//                )
//        );
//        $this->addElement($username);

        $idMember = App_Utilities::getIdMember();

        $member_id = new Zend_Form_Element_Hidden('member_id');
        $member_id->addFilter('Int')
                ->setValue($idMember);
        $this->addElement($member_id);

        $firstname = new Zend_Form_Element_Text('first_name');
        $firstname->setOptions(
                array(
                    'label' => $this->t('First name'),
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
        $this->addElement($firstname);

        $lastname = new Zend_Form_Element_Text('last_name');
        $lastname->setOptions(
                array(
                    'label' => $this->t('Last name'),
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
        $this->addElement($lastname);
        $email = new Zend_Form_Element_Text('email');
        $email->setOptions(
                array(
                    'label' => $this->t('Email address'),
                    'required' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
                        'EmailAddress',
                    ),
                )
        );
        $this->addElement($email);

        $birthdate = new Zend_Form_Element_Text('birthdate');
        $birthdate->setOptions(
                array(
                    'label' => $this->t('birthdate'),
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
        $this->addElement($birthdate);

        $city = new Zend_Form_Element_Text('name');
        $city->setOptions(
                array(
                    'label' => $this->t('city'),
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
        $this->addElement($city);

        $phoneNumber = new Zend_Form_Element_Text('phone_number');
        $phoneNumber->setOptions(
                array(
                    'label' => $this->t('Phone number'),
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
        $this->addElement($phoneNumber);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
                array(
                    'label' => $this->t('Save profile'),
                    'required' => true,
                )
        );
        $this->addElement($submit);
    }

}