<?php

/**
 * Form for adding new privileges in the application
 *
 * @category backoffice
 * @package backoffice_forms
 * @copyright company
 */
class UrlRewritingForm extends App_Backoffice_Form {

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

//source 	
//alias 	aliasNotLogged 	viewIdentifier 	viewTitle 	params 	isStatic 	
//metaKeywords 	metaDescription

        $id = new Zend_Form_Element_Hidden('urlAliasId');
        $id->setOptions(
                array(
                    'validators' => array(
                        // either empty or numeric
                        new Zend_Validate_Regex('/^\d*$/'),
                    ),
                )
        );
        $this->addElement($id);

        $source = new Zend_Form_Element_Text('source');
        $source->setOptions(
                array(
                    'label' => 'Source',
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
        $this->addElement($source);

        $alias = new Zend_Form_Element_Text('alias');
        $alias->setOptions(
                array(
                    'label' => 'Alias',
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
        $this->addElement($alias);

        $params = new Zend_Form_Element_Text('params');
        $params->setOptions(
                array(
                    'value' => '{"module":"frontend","controller":"__","action":"__"}',
                    'label' => 'Params',
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
                    ),
                )
        );
        $this->addElement($params);

        $isStatic = new Zend_Form_Element_Text('isStatic');
        $isStatic->setOptions(
                array(
                    'label' => 'isStatic',
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
        $this->addElement($isStatic);

        $metaKeywords = new Zend_Form_Element_Text('metaKeywords');
        $metaKeywords->setOptions(
                array(
                    'label' => 'metaKeywords',
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
        $this->addElement($metaKeywords);

        $metaDescription = new Zend_Form_Element_Text('metaDescription');
        $metaDescription->setOptions(
                array(
                    'label' => 'metaDescription',
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
        $this->addElement($metaDescription);

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