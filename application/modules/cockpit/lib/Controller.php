<?php

class ZFS_Cockpit_Controller extends ZFS_Default_Controller
{
    public function init() {
        // if the current controller is not auth;
        if ('auth' != $this->getRequest()->getControllerName()) {
            // if the user is not logged in;
            if (! Zend_Auth::getInstance()->hasIdentity()) {
                // redirect the user to the login page.
                $this->_redirect('/cockpit/auth/login');
            }
        }
        
        parent::init();
        
        // initialize the error messages instance.
        $this->view->errorMessages = new ErrorMessage();
        
        // if the user is logged in;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // set the currently logged in users data.
            $this->view->currentUser = Zend_Auth::getInstance()->getIdentity();
        }
        
        // set the breadcrumbs.
        $this->view->breadCrumbs = $this->_helper->Breadcrumbs();
        
        // fetch the system settings.
        $this->view->systemConfig = Zend_Registry::get('SYSTEM_CONFIG');
        
        // fetch the user.
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = $this->_doctrineContainer->getEntityManager()
                         ->getRepository('App\Entity\User')
                         ->find(Zend_Auth::getInstance()->getIdentity()->id);

            $this->view->role = $user->roles[0]->name;
        }
    }
}

class ErrorMessage implements ArrayAccess, Countable
{
    public function offsetExists($offset) {
        return isset($this->{$offset});
    }
    
    public function offsetGet($offset) {
        if ($this->offsetExists($offset)) {
            return $this->{$offset};
        }
        
        return array('message' => '');
    }
    
    public function offsetSet($offset, $value) {
        $this->{$offset} = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->{$offset});
    }

    public function count() {
        return count(get_object_vars($this));
    }
    
    public function toArray() {
        return get_object_vars($this);
    }
}