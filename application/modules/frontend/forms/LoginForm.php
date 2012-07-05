<?php

/**
 * User login form
 *
 * @category backoffice
 * @package backoffice_forms
 * @copyright company
 */
class LoginForm extends App_Frontend_Form {

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

        // $this->t = Zend_Registry::get('Zend_Translate');


        $username->setOptions(
                array(
                    'label' => $this->t('Username'),
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
        $this->addElement($username);

        $password = new Zend_Form_Element_Password('password');
        $password->setOptions(
                array(
                    'label' => $this->t('Password'),
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
        $this->addElement($password);

        $authentification = new LoginAttempt();
        if ($authentification->canAttemptToLogin() == FALSE) {
            $captcha = new Zend_Form_Element_Captcha('captcha', array(
                        'label' => $this->t("no humain"),
                        // paramétrage en reprenant les noms de méthodes vus précédemment
                        'captcha' => array(
                            "captcha" => "Image",
                            "wordLen" => 4,
                            "font" => "font/tahoma.ttf",
                            "height" => 100,
                            "width" => 300,
                            "fontSize" => 50,
                            "imgDir" => "data/captchas",
                            "imgUrl" => "data/captchas"
                        )
                    ));

            $this->addElement($captcha);
        }
        $connexion = new Zend_Form_Element_Submit('Connexion');
        $connexion->setOptions(
                array(
                    'label' => $this->t('Log In')
                )
        );
        $connexion->setDecorators(array(
            'ViewHelper'
            , array('HtmlTag', array('tag' => 'dd', 'openOnly' => true))
        ));
        $this->addElement($connexion);



        $inscription = new Zend_Form_Element_Submit('inscription');
        $inscription->setOptions(
                array(
                    'label' => $this->t('Sign Up')
                )
        );
        $inscription->setDecorators(array(
            'ViewHelper'
            , array('HtmlTag', array('tag' => 'dd', 'closeOnly' => true))
        ));
        $this->addElement($inscription);
        $this->clearDecorators();
    }

}