<?php

class ZFS_Form_Cockpit_Doctor extends Zend_Form
{
    public function __construct($data = array())
    {
        parent::__construct();
        
        $operation = 'register';
        $id = null;
        if ($data instanceof \App\Entity\Doctor) {
            $operation = 'update';
            $id = $data->id;
        }

        // init the form and add corresponding elements.
        $this->_init($operation, $id)
             
             // basic information.
             ->_addNameElement(SE_Util::getValueFromInput($data, 'name'))
             ->_addAreaElement(SE_Util::getValueFromInput($data, 'area'))
             ->_addEmailElement(SE_Util::getValueFromInput($data, 'email'))
             ->_addPasswordElement(SE_Util::getValueFromInput($data, 'password'))
             ->addDisplayGroup(array('name', 'area', 'email', 'password'), 'basic_information')
             ->_addSubmitElement($operation, $id);
        
        $this->getDisplayGroup('basic_information')->removeDecorator('HtmlTag');
        $this->getDisplayGroup('basic_information')->removeDecorator('DtDdWrapper');
        $this->getDisplayGroup('basic_information')->removeDecorator('Fieldset');
        $this->getDisplayGroup('basic_information')->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'fieldset-basic_information'));
        
        return $this;
    }
    
    protected function _init($operation, $id)
    {
        // fetch the view instance.
        $view = Zend_Layout::getMvcInstance()->getView();
        
        if ('update' == $operation) {
            $this->setAction($view->baseUrl('/cockpit/doctor/' . $id));
        } else {
            $this->setAction($view->baseUrl('/cockpit/doctor/register'));
        }
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');

        // remove the DtDdWrapper decorator.
        $this->removeDecorator('HtmlTag');
        
        // return the form.
        return $this;
    }
    
    protected function _addNameElement($value) {
        $textElement = new Zend_Form_Element_Text('name');
        $textElement->setLabel('Name')
                    ->setAttrib('maxlength', 50)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter name!'
                                )
                            )
                        )
                    ));
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/text.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addAreaElement($value) {
        $textElement = new Zend_Form_Element_Text('area');
        $textElement->setLabel('area')
                    ->setAttrib('maxlength', 50)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter area!'
                                )
                            )
                        )
                    ));
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/text.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addEmailElement($value) {
        $textElement = new Zend_Form_Element_Text('email');
        $textElement->setLabel('email')
                    ->setAttrib('maxlength', 255)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter email!'
                                )
                            )
                        )
                    ));
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/text.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addPasswordElement($value) {
        $textElement = new Zend_Form_Element_Text('password');
        $textElement->setLabel('password')
                    ->setAttrib('maxlength', 50)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter password!'
                                )
                            )
                        )
                    ));
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/text.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addSubmitElement($operation, $id) {
        // add the operation type element.
        $operationTypeElement = new Zend_Form_Element_Hidden('operation');
        $operationTypeElement->setValue('register_patient');
        $operationTypeElement->removeDecorator('HtmlTag');
        $operationTypeElement->removeDecorator('Label');
        $this->addElement($operationTypeElement);
        
        $operationTypeElement = new Zend_Form_Element_Hidden('id');
        $operationTypeElement->setValue($id);
        $operationTypeElement->removeDecorator('HtmlTag');
        $operationTypeElement->removeDecorator('Label');
        $this->addElement($operationTypeElement);

        // create the submit element
        $submitElement = new Zend_Form_Element_Submit('submit_patient_register');
        
        if ('update' == $operation) {
            $submitElement->setLabel('Update Doctor');
        } else {
            $submitElement->setLabel('Register Doctor');
        }
        
        $submitElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/submit.phtml',
                    'o' => $submitElement
            )),
            array('HtmlTag', array('tag' => 'p', 'class' => 'widthFull innerRight'))
        ));

        // add the element to the form
        $this->addElement($submitElement);

        // return the form
        return $this;
    }
    
    // -------------------------------------------------------------------------
    // form operations.
    // -------------------------------------------------------------------------
    public static function save($data) {
        return \App\Entity\Doctor::register($data);
    }
    
    public static function update($data) {
        return \App\Entity\Doctor::update($data);
    }
}