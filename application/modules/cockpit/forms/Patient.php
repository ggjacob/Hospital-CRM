<?php

class ZFS_Form_Cockpit_Patient extends Zend_Form
{
    public function __construct($data = array())
    {
        parent::__construct();
        
        // init the form and add corresponding elements.
        $this->_init()
             
             // basic information.
             ->_addNameElement(SE_Util::getValueFromInput($data, 'name'))
             ->_addSurnameElement(SE_Util::getValueFromInput($data, 'surname'))
             ->_addBirthdateElement(SE_Util::getValueFromInput($data, 'birthdate'))
             ->_addGenderElement(SE_Util::getValueFromInput($data, 'gender'))
             ->_addTCIdentityNumberElement(SE_Util::getValueFromInput($data, 'TCIdentityNumber'))
             ->_addAddressElement(SE_Util::getValueFromInput($data, 'address'))
             ->_addContactNumberElement(SE_Util::getValueFromInput($data, 'contactNumber'))
             ->_addPaymentAmountElement(SE_Util::getValueFromInput($data, 'paymentAmount'))
             ->_addPaymentIsMadeElement(SE_Util::getValueFromInput($data, 'paymentIsMade'))
             ->_addTypeElement(SE_Util::getValueFromInput($data, 'type'))
             ->addDisplayGroup(array('name', 'surname', 'birthdate', 'gender', 'TCIdentityNumber', 'address', 'contactNumber', 'paymentAmount', 'paymentIsMade', 'type'), 'basic_information')
             
             // outpatient.
             ->_addComplaintsElement(SE_Util::getValueFromInput($data, 'complaints'))
             ->_addHistoryElement(SE_Util::getValueFromInput($data, 'history'))
             ->_addDiagnosisElement(SE_Util::getValueFromInput($data, 'diagnosis'))
             ->_addAdviceElement(SE_Util::getValueFromInput($data, 'advice'))
             ->addDisplayGroup(array('complaints', 'history', 'diagnosis', 'advice'), 'outpatient_information')
                
             // inpatient.
             ->_addRoomNumberElement(SE_Util::getValueFromInput($data, 'roomNumber'))
             ->_addOperationIsRequiredElement(SE_Util::getValueFromInput($data, 'operationIsRequired'))
             ->addDisplayGroup(array('roomNumber', 'operationIsRequired'), 'inpatient_information')
                
             ->_addSubmitElement();
        
        $this->getDisplayGroup('basic_information')->removeDecorator('HtmlTag');
        $this->getDisplayGroup('basic_information')->removeDecorator('DtDdWrapper');
        $this->getDisplayGroup('basic_information')->removeDecorator('Fieldset');
        $this->getDisplayGroup('basic_information')->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'fieldset-basic_information'));
        $this->getDisplayGroup('outpatient_information')->removeDecorator('HtmlTag');
        $this->getDisplayGroup('outpatient_information')->removeDecorator('DtDdWrapper');
        $this->getDisplayGroup('outpatient_information')->removeDecorator('Fieldset');
        $this->getDisplayGroup('outpatient_information')->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'fieldset-outpatient_information'));
        $this->getDisplayGroup('inpatient_information')->removeDecorator('HtmlTag');
        $this->getDisplayGroup('inpatient_information')->removeDecorator('DtDdWrapper');
        $this->getDisplayGroup('inpatient_information')->removeDecorator('Fieldset');
        $this->getDisplayGroup('inpatient_information')->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'fieldset-inpatient_information'));
        
        return $this;
    }
    
    protected function _init()
    {
        // fetch the view instance.
        $view = Zend_Layout::getMvcInstance()->getView();
        
        $this->setAction($view->baseUrl('/cockpit/patient/register'));
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
    
    protected function _addSurnameElement($value) {
        $textElement = new Zend_Form_Element_Text('surname');
        $textElement->setLabel('Surname')
                    ->setAttrib('maxlength', 50)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter surname!'
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
    
    protected function _addBirthdateElement($value) {
        $textElement = new Zend_Form_Element_Text('birthdate');
        $textElement->setLabel('Birthdate')
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Select the birthdate!'
                                )
                            )
                        )
                    ));
        
        // set the value if needed.
        if (! empty($value)) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/date.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);
        
        // return the form
        return $this;
    }
    
    protected function _addGenderElement($value) {
        $checkboxElement = new Zend_Form_Element_Radio('gender');
        $checkboxElement->addMultiOptions(array(
            \App\Entity\User\Gender::MALE => 'Male',
            \App\Entity\User\Gender::FEMALE => 'Female',
        ));
        $checkboxElement->setValue($value);
        $checkboxElement->removeDecorator('HtmlTag');
        $checkboxElement->removeDecorator('DtDdWrapper');
        $checkboxElement->removeDecorator('Label');
        $checkboxElement->addDecorator('HtmlTag', array('tag' => 'p', 'class' => 'radio'));

        // add the element to the form.
        $this->addElement($checkboxElement);
        
        // return the form
        return $this;
    }
    
    protected function _addTCIdentityNumberElement($value) {
        $textElement = new Zend_Form_Element_Text('TCIdentityNumber');
        $textElement->setLabel('TC Identity Number (Social Security Number)')
                    ->setAttrib('maxlength', 11)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Enter TC Identity Number!'
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
    
    protected function _addAddressElement($value) {
        $textElement = new Zend_Form_Element_Textarea('address');
        $textElement->setLabel('Address');
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/textarea.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addContactNumberElement($value) {
        $textElement = new Zend_Form_Element_Text('contactNumber');
        $textElement->setLabel('Contact Number')
                    ->setAttrib('maxlength', 20);
        
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
    
    protected function _addPaymentAmountElement($value) {
        $textElement = new Zend_Form_Element_Text('paymentAmount');
        $textElement->setLabel('Payment Amount (in TL; use dot for decimal points; e.g. 10.20)')
                    ->setAttrib('maxlength', 10);
        
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
    
    protected function _addPaymentIsMadeElement($value) {
        $checkboxElement = new Zend_Form_Element_Checkbox('paymentIsMade');
        $checkboxElement->setLabel('The patient paid the required amount!');
        $checkboxElement->setValue($value);

        $checkboxElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/checkbox.phtml',
                    'o' => $checkboxElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($checkboxElement);
        
        // return the form
        return $this;
    }
    
    protected function _addTypeElement($value) {
        $textElement = new Zend_Form_Element_Select('type');
        $textElement->setLabel('Patient Type')
                    ->setAttrib('values', array(
                        \App\Entity\Patient\Type::OUTPATIENT => 'Outpatient',
                        \App\Entity\Patient\Type::INPATIENT => 'Inpatient'
                    ))
                    ->setAttrib('id', 'patientType')
                    ->setRegisterInArrayValidator(false)
                    ->setRequired()
                    ->addValidators(array(
                        array(
                            'validator' => 'NotEmpty',
                            'breakChainOnFailure' => true,
                            'options' => array(
                                'messages' => array(
                                    Zend_Validate_NotEmpty::IS_EMPTY => 'Select patient type!'
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
                    'viewScript' => '/decorators/select.phtml',
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
        $operationTypeElement->setValue('register_patient');
        $operationTypeElement->removeDecorator('HtmlTag');
        $operationTypeElement->removeDecorator('Label');
        $this->addElement($operationTypeElement);

        // create the submit element
        $submitElement = new Zend_Form_Element_Submit('submit_patient_register');
        $submitElement->setLabel('Register Patient');
        
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
    // outpatient.
    // -------------------------------------------------------------------------
    protected function _addComplaintsElement($value) {
        $textElement = new Zend_Form_Element_Textarea('complaints');
        $textElement->setLabel('Complaints');
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/textarea.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addHistoryElement($value) {
        $textElement = new Zend_Form_Element_Textarea('history');
        $textElement->setLabel('History');
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/textarea.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addDiagnosisElement($value) {
        $textElement = new Zend_Form_Element_Textarea('diagnosis');
        $textElement->setLabel('Diagnosis');
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/textarea.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    protected function _addAdviceElement($value) {
        $textElement = new Zend_Form_Element_Textarea('advice');
        $textElement->setLabel('Advice');
        
        // set the value if needed.
        if (! empty($value)) {
            $textElement->setValue($value);
        }

        $textElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/textarea.phtml',
                    'o' => $textElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($textElement);

        // return the form
        return $this;
    }
    
    // -------------------------------------------------------------------------
    // inpatient.
    // -------------------------------------------------------------------------
    protected function _addRoomNumberElement($value) {
        $textElement = new Zend_Form_Element_Text('roomNumber');
        $textElement->setLabel('Room number')
                    ->setAttrib('maxlength', 5);
        
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
    
    protected function _addOperationIsRequiredElement($value) {
        $checkboxElement = new Zend_Form_Element_Checkbox('operationIsRequired');
        $checkboxElement->setLabel('Operation is required');
        $checkboxElement->setChecked($value);
        $checkboxElement->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => '/decorators/checkbox.phtml',
                    'o' => $checkboxElement
            )),
            array('HtmlTag', array('tag' => 'p'))
        ));

        // add the element to the form.
        $this->addElement($checkboxElement);
        
        // return the form
        return $this;
    }
    
    // -------------------------------------------------------------------------
    // form operations.
    // -------------------------------------------------------------------------
    public static function save($data) {
        if (\App\Entity\Patient\Type::OUTPATIENT == $data['type']) {
            return \App\Entity\Patient\Out::register($data);
        } else if (\App\Entity\Patient\Type::INPATIENT == $data['type']) {
            return \App\Entity\Patient\In::register($data);
        } else {
            throw new Exception('Patient type is not supported!');
        }
    }
}