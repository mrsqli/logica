<?php

/**
 * Form that allows the user to add/ update his study
 * @author EL GUENNUNI Sohaib s.elguennuni@gmail.com
 * @uses Zend_Form
 * @category FrontOffice
 * @package form
 */
class StudyForm extends App_Frontend_Form {

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
        $schoolName = new Zend_Form_Element_Text('school');
        $schoolName->setOptions(
                array(
                    'label' => $this->t('School Name'),
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
        $this->addElement($schoolName);
        $domain = new Zend_Form_Element_Text('domain');
        $domain->setOptions(
                array(
                    'label' => $this->t('domain'),
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
        $this->addElement($domain);
        $degree = new Zend_Form_Element_Text('degree');
        $degree->setOptions(
                array(
                    'label' => $this->t('Degree'),
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
        $this->addElement($degree);

        $result = new Zend_Form_Element_Text('result');
        $result->setOptions(
                array(
                    'label' => $this->t('Result'),
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
        $this->addElement($result);

        $activity = new Zend_Form_Element_Text('activity');
        $activity->setOptions(
                array(
                    'label' => $this->t('Activity'),
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
        $this->addElement($activity);

        $information_add = new Zend_Form_Element_Text('information_add');
        $information_add->setOptions(
                array(
                    'label' => $this->t('Additional Information'),
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
        $this->addElement($information_add);

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