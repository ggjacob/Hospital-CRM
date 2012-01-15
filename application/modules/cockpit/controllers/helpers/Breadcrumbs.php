<?php

class ZFS_Cockpit_Controller_Helper_Breadcrumbs extends Zend_Controller_Action_Helper_Abstract
{
    public function direct() {
        // determine the current controller.
        $controller = $this->getRequest()->getControllerName();
        
        // determine the current action.
        $action = $this->getRequest()->getActionName();
        
        // set controller description and link.
        $controllerDesc = '';
        $controllerLink = '';
        $actionDesc = '';
        switch ($controller) {
            case 'index':
                $controllerDesc = 'Dashboard';
                $controllerLink = '';
                // set the action description.
                switch ($action) {
                    case 'index':
                        $actionDesc = 'Dashboard';
                        break;
                }
                break;
            case 'auth':
                $controllerDesc = 'Authentication Mechanism';
                $controllerLink = 'login';
                // set the action description.
                switch ($action) {
                    case 'login':
                        $actionDesc = 'Login';
                }
                break;
        }

        // return the breadcrumbs string.
        return <<<EOT
<article class="breadcrumbs">
    <a href="{$this->getActionController()->view->baseUrl("/cockpit")}">Hospital CRM Dashbord</a>
    <div class="breadcrumb_divider"></div>
    <a href="{$this->getActionController()->view->baseUrl("/cockpit/" . $controllerLink)}">$controllerDesc</a>
    <div class="breadcrumb_divider"></div>
    <a class="current">$actionDesc</a>
</article>        
EOT;
    }
}