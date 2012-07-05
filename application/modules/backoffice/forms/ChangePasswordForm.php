<?php
/**
 * Form that allows the user to change his/her password
 *
 * @category backoffice
 * @package backoffice
 * @subpackage backoffice_forms
 * @copyright company
 */

class ChangePasswordForm extends App_Backoffice_Form
{
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
        
        $user = Zend_Auth::getInstance()->getIdentity();
        
        $oldPasswordValidator = new App_Validate_PasswordExists(
            array(
                'table' => 'backoffice_users',
                'field' => 'password',
                'treatment' => 'BackofficeUser::hashPassword',
                'userPkValue' => $user->id,
            )
        );
        
        $complexityValidator = new Zend_Validate_Regex('/^(?=.*\d)(?=.*[a-z|A-Z]).{7,}$/');
        $complexityValidator->setMessage('The selected password does not meet the required complexity requirements');
        
        $stringLengthValidator = new Zend_Validate_StringLength();
        $stringLengthValidator->setMin(7);
        $stringLengthValidator->setMessage('Your password must be at least 7 characters long');
        
//        $passwordHistoryValidator = new App_Validate_NoPasswordExists(
//            array(
//                'table' => 'password_log',
//                'field' => 'password',
//                'treatment' => 'BackofficeUser::hashPassword',
//                'userPkField' => 'user_id',
//                'userPkValue' => $user->id,
//            )
//        );
        
        $oldPassword = new Zend_Form_Element_Password('old_password');
        $oldPassword->setOptions(
            array(
                'label'      => 'Old password',
                'required'   => TRUE,
                'filters'    => array(
                                    'StringTrim',
                                    'StripTags',
                                ),
                'validators' => array(
                                    'NotEmpty',
                                    $oldPasswordValidator,
                                ),
            )
        );
        $this->addElement($oldPassword);
        
        $password = new Zend_Form_Element_Password('password');
        $password->setOptions(
            array(
                'label'      => 'New password',
                'required'   => TRUE,
                'filters'    => array(
                                    'StringTrim',
                                    'StripTags',
                                ),
                'validators' => array(
                                    'NotEmpty',
                                    $stringLengthValidator,
                                    $complexityValidator,
                                    //$passwordHistoryValidator,
                                ),
            )
        );
        $this->addElement($password);
        
        $sameAsValidator = new App_Validate_SameAs($password);
        $sameAsValidator->setMessage('The two passwords do not coincide.', 
                                     App_Validate_SameAs::NOT_THE_SAME);
        
        $retypeNewPassword = new Zend_Form_Element_Password('retype_new_password');
        $retypeNewPassword->setOptions(
            array(
                'label'      => 'Retype new password',
                'required'   => TRUE,
                'filters'    => array(
                                    'StringTrim',
                                    'StripTags',
                                ),
                'validators' => array(
                                    'NotEmpty',
                                    $sameAsValidator,
                                ),
            )
        );
        $this->addElement($retypeNewPassword);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
            array(
                'label'      => 'Save password',
                'required'   => TRUE,
            )
        );
        $this->addElement($submit);
        
    }
}