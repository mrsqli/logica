<?php
/**
 * User login form
 *
 * @category backoffice
 * @package backoffice_forms
 * @copyright company
 */

class LoginForm extends App_Backoffice_Form
{
    /**
     * This form does not have a cancel link
     * 
     * @var mixed
     * @access protected
     */
    protected $_cancelLink = false;
    
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
        
        $username = new Zend_Form_Element_Text('username');
        $username->setOptions(
            array(
                'label'      => 'Username',
                'required'   => true,
                'filters'    => array(
                                    'StringTrim',
                                    'StripTags',
                                ),
                'validators' => array(
                                    'NotEmpty',
                                ),
            )
        );
        $this->addElement($username);
        
        $password = new Zend_Form_Element_Password('password');
        $password->setOptions(
            array(
                'label'      => 'Password',
                'required'   => true,
                'filters'    => array(
                                    'StringTrim',
                                    'StripTags',
                                ),
                'validators' => array(
                                    'NotEmpty',
                                ),
            )
        );
        $this->addElement($password);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
            array(
                'label'      => 'Log in →',
                'required'   => true,
            )
        );
        $this->addElement($submit);
    }
}