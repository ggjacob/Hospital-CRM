<?php

class Cockpit_AuthController extends ZFS_Cockpit_Controller
{
    public function init() {
        parent::init();
        
        // change the default layout file.
        $this->_helper->layout->setLayout('login');
    }
    
    public function loginAction() {
        // if the user is already logged in;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // redirect the user to the dashboard.
            $this->_redirect('/cockpit');
        }
        
        // local variable.
        $request = $this->getRequest();
        
        // if the current request is of type "POST";
        if ($request->isPost()) {
            // if the current operation is "login";
            if ('login' == $request->getPost('operation')) {
                // create the form using the post data.
                $form = new ZFS_Form_Cockpit_Login(
                    $request->getPost()
                );
                
                // if the user information is ok;
                if ($form->isValid($request->getPost())) {
                    // if the user can be logged in;
                    if ($this->_helper->emailAuthentication(
                        $request->getPost('email'),
                        $request->getPost('password')))
                    {
                        // redirect the user to the dashboard.
                        $this->_redirect('/cockpit');
                    }
                    else
                    {
                        // set the error message.
                        $this->view->errorMessages['login'] = array(
                            'message' => 'Login operation failed! Please try again!'
                        );
                    }
                } else {
                    // set the error messages.
                    $this->view->errorMessages['login'] = $form->getMessages();
                }
            } else {
                throw new Exception('An unknown operation is requested!');
                return;
            }
        } else {
            $form = new ZFS_Form_Cockpit_Login();
        }
        
        // set the form for the view.
        $this->view->form = $form;
    }
    
    public function logoutAction() {
        // if the user is already logged in;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // clear the identity.
            Zend_Auth::getInstance()->clearIdentity();
        }
        
        // redirect the user to the login page.
        $this->_redirect('/cockpit/auth/login');
    }
}