<?php

/**
 * Form for editing users
 *
 *
 * @category backoffice
 * @package backoffice_forms
 * @copyright company
 */
class UserProspectForm extends App_Backoffice_Form {

    /**
     * Overrides init() in Zend_Form
     * 
     * @access public
     * @return void
     */
    public function emailNotJetable($email) {
        $jetables = new JunkMail();

        $domain = explode('@', $email);

        return !in_array(array('email'=>$domain[1]), $jetables->listJunkMail());
    }

    public function init() {
// init the parent
        parent::init();

// set the form's method
        $this->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setOptions(
                array(
                    'validators' => array(
// either empty or numeric
                        new Zend_Validate_Regex('/^\d*$/'),
                    ),
                )
        );
        $this->addElement($id);

        $firstname = new Zend_Form_Element_Text('firstname');
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

        $lastname = new Zend_Form_Element_Text('lastname');
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


        $checkEmailNotJunk = new Zend_Validate_Callback(array($this, 'emailNotJetable'));


        $uniqueEmailValidator = new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'backoffice_users',
                            'field' => 'email',
                        )
        );

        $email = new Zend_Form_Element_Text('email');
        $email->setOptions(
                array(
                    'label' => $this->t('Email'),
                    'required' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
                        $checkEmailNotJunk,
                        $uniqueEmailValidator,
                    ),
                )
        );
        $this->addElement($email);

        $raisonsocial = new Zend_Form_Element_Text('raison sociale');
        $raisonsocial->setOptions(
                array(
                    'label' => $this->t('Raison sociale'),
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
        $this->addElement($raisonsocial);

//        $groupsInArrayValidator = new Zend_Validate_InArray(array_keys(array(1, 2, 3)));
//        $groupsInArrayValidator->setMessage('Please select at least one group. If you are not sure about which group is better, select "member".');
        $status = new Zend_Form_Element_Radio('status');
        $status->setOptions(
                array(
                    'label' => $this->t('Status'),
                    'required' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    //  $groupsInArrayValidator
                    ),
                    'validators' => array(
                        'NotEmpty',
                    ),
                    'multiOptions' => array('Gérant'=>'Gérant', 'Associé'=>'Associé', 'Freelance patenté'=>'Freelance patenté'),
                )
        );
        $this->addElement($status);



        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
                array(
                    'label' => $this->t('Save user'),
                    'required' => true,
                    'order' => 100,
                )
        );
        $this->addElement($submit);
    }

    /**
     * Overrides isValid() in App_Form
     * 
     * @param array $data 
     * @access public
     * @return bool
     */
    public function isValid($data) {
        if (isset($data['id']) && is_numeric($data['id'])) {
            $this->getElement('username')
                    ->getValidator('Zend_Validate_Db_NoRecordExists')
                    ->setExclude(array(
                        'field' => 'id',
                        'value' => $data['id']
                    ));

            $this->getElement('email')
                    ->getValidator('Zend_Validate_Db_NoRecordExists')
                    ->setExclude(array(
                        'field' => 'id',
                        'value' => $data['id']
                    ));
        }

        return parent::isValid($data);
    }

}