<?php

class ZFS_Form_Cockpit_Login extends Zend_Form
{
    public function __construct($data = array())
    {
        parent::__construct();
        
        // init the form and add corresponding elements.
        $this->_init()
             ->_addEmailElement($this->getValueFromInput($data, 'email'))
             ->_addPasswordElement($this->getValueFromInput($data, 'password'))
             ->_addSubmitElement();
        
        return $this;
    }
    
    protected function _init()
    {
        // fetch the view instance.
        $view = Zend_Layout::getMvcInstance()->getView();
        
        $this->setAction($view->baseUrl('/cockpit/auth/login'));
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');

        // remove the DtDdWrapper decorator.
        $this->removeDecorator('HtmlTag');
        
        // return the form.
        return $this;
    }
    
    protected function _addEmailElement($value) {
        $textElement = new Zend_Form_Element_Text('email');
        $textElement->setLabel('Email address')
                    ->setAttrib('maxlength', 255)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter your email address!'
                                )
                            )
                        ),
                        array(
                            'validator' => 'EmailAddress',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_EmailAddress::INVALID => 'Please enter a valid email address!',
                                    Zend_Validate_EmailAddress::INVALID_FORMAT => 'Please enter a valid email address!'
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
        $textElement = new Zend_Form_Element_Password('password');
        $textElement->setLabel('Password')
                    ->setAttrib('maxlength', 12)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter your password!'
                                )
                            )
                        ),
                        array(
                            'validator' => 'StringLength',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'min' => 4,
                                'messages' => array(
                                    Zend_Validate_StringLength::TOO_SHORT => 'The password should contain at least 4 characters!'
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
                    'viewScript' => '/decorators/password.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
 
    protected function _addSubmitElement() {
        // add the operation type element.
        $operationTypeElement = new Zend_Form_Element_Hidden('operation');
        $operationTypeElement->setValue('login');
        $operationTypeElement->removeDecorator('HtmlTag');
        $operationTypeElement->removeDecorator('Label');
        $this->addElement($operationTypeElement);

        // create the submit element
        $submitElement = new Zend_Form_Element_Submit('submit_login');
        $submitElement->setLabel('Login');
        
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
    
    protected function getValueFromInput($data, $key) {
        if (is_array($data) && isset($data[$key])) {
            return $data[$key];
        } else if (is_object($data) && isset($data->{$key})) {
            return $data->{$key};
        }
        
        return null;
    }
}