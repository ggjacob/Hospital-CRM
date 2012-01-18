<?php

class Cockpit_PatientController extends ZFS_Cockpit_Controller
{
    public function registerAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            if ('register_patient' == $request->getPost('operation')) {
                $form = new ZFS_Form_Cockpit_Patient(
                    $request->getPost()
                );
                
                if ($form->isValid($request->getPost())) {
                    if (ZFS_Form_Cockpit_Patient::save($request->getPost()))
                    {
                        $this->_redirect('/cockpit/patient');
                    }
                    else
                    {
                        // set the error message.
                        $this->view->errorMessages['register_patient'] = array(
                            'message' => 'Patient could not be registered!'
                        );
                    }
                } else {
                    // set the error messages.
                    $this->view->errorMessages['register_patient'] = $form->getMessages();
                }
            } else {
                throw new Exception('An unknown operation type is requested!');
                return;
            }
        } else {
            $form = new ZFS_Form_Cockpit_Patient();
        }
        
        // set the form for the view.
        $this->view->form = $form;
    }
}