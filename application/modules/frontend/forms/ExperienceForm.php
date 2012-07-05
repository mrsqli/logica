<?php

/**
 * Form that allows the user to add/update his experience
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class ExperienceForm extends App_Frontend_Form {

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

        $start_date = new Zend_Form_Element_Text('start_date');
        $start_date->setOptions(
                array(
                    'label' => $this->t('Start date'),
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
        $this->addElement($start_date);

        $end_date = new Zend_Form_Element_Text('end_date');
        $end_date->setOptions(
                array(
                    'label' => $this->t('End Date'),
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
        $this->addElement($end_date);

        $is_posted = new Zend_Form_Element_Checkbox('is_posted');
        $this->addElement($is_posted);

        $companyName = new Zend_Form_Element_Text('name');
        $companyName->setOptions(
                array(
                    'label' => $this->t('Company'),
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
        $this->addElement($companyName);


        $function = new Zend_Form_Element_Text('function');
        $function->setOptions(
                array(
                    'label' => $this->t('function'),
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
        $this->addElement($function);

        $mission = new Zend_Form_Element_Textarea('mission');
        $mission->setOptions(
                array(
                    'label' => $this->t('mission'),
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
        $this->addElement($mission);

        $city_name = new Zend_Form_Element_Text('city_name');
        $city_name->setOptions(
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
        $this->addElement($city_name);
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