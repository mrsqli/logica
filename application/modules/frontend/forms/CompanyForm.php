<?php

/**
 * Form that allows the user to add/update his company
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class CompanyForm extends App_Frontend_Form {

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


        $nameCompany = new Zend_Form_Element_Text('name');
        $nameCompany->setOptions(
                array(
                    'label' => $this->t('Raison social'),
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
        $this->addElement($nameCompany);


        $synopsis = new Zend_Form_Element_Textarea('synopsis');
        $synopsis->setOptions(
                array(
                    'label' => $this->t('synopsis'),
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
        $this->addElement($synopsis);
        
        $file = new Zend_Form_Element_File('logo');
        $file->setLabel('image')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setDestination(APPLICATION_PATH . '/../public/frontend/images/logo/')
                ->setRequired(false);

        $this->addElement($file);

        $rc = new Zend_Form_Element_Text('rc');
        $rc->setOptions(
                array(
                    'label' => $this->t('rc'),
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
        $this->addElement($rc);
        $patente = new Zend_Form_Element_Text('patente');
        $patente->setOptions(
                array(
                    'label' => $this->t('patente'),
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
        $this->addElement($patente);
        $createDate = new Zend_Form_Element_Text('create_date');
        $createDate->setOptions(
                array(
                    'label' => $this->t('create_date'),
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
        $this->addElement($createDate);
        $tel = new Zend_Form_Element_Text('tel');
        $tel->setOptions(
                array(
                    'label' => $this->t('tel'),
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
        $this->addElement($tel);
        $fax = new Zend_Form_Element_Text('fax');
        $fax->setOptions(
                array(
                    'label' => $this->t('fax'),
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
        $this->addElement($fax);
        $adress = new Zend_Form_Element_Text('adress');
        $adress->setOptions(
                array(
                    'label' => $this->t('adress'),
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
        $this->addElement($adress);
        $description = new Zend_Form_Element_Text('description');
        $description->setOptions(
                array(
                    'label' => $this->t('description'),
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
        $this->addElement($description);




        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setOptions(
                array(
                    'label' => $this->t('Save'),
                    'required' => true,
                )
        );
        $this->addElement($submit);
    }

}