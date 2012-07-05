<?php

/**
 * Form that allows the user to add/update his profil(image,description)
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class ProfilDescription extends App_Frontend_Form {

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

        $idMember = App_Utilities::getIdMember();

        $member_id = new Zend_Form_Element_Hidden('member_id');
        $member_id->addFilter('Int')
                ->setValue($idMember);
        $this->addElement($member_id);


        $file = new Zend_Form_Element_File('image_url');
        $file->setLabel('image')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setDestination(APPLICATION_PATH . '/../public/frontend/images/profil/')
                ->setRequired(false);

        $this->addElement($file);

        $profil_description = new Zend_Form_Element_Textarea('profil_description');
        $profil_description->setOptions(
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
        $this->addElement($profil_description);

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