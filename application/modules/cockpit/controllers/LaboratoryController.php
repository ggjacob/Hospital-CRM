<?php

class Cockpit_LaboratoryController extends ZFS_Cockpit_Controller
{
    public function requestAction() {
        // fetch the entity manager.
        $em = $this->_doctrineContainer->getEntityManager();

        $this->view->requests = $em->getRepository('App\Entity\LabRequest')
                                     ->findBy(array(
                                         'status' => \App\Entity\LabRequest\Status::WAITING
                                     ));
    }
    
    public function respondAction()
    {
        $request = $this->getRequest();
        
        $requestId = (int) $request->getParam('requestId');

        $response = $request->getParam('response');

        // schedule the meeting.
        \App\Entity\LabRequest::response(array(
            'labRequestId' => $requestId,
            'details' => $response
        ));
        
        $this->_redirect('/cockpit/lab/request');
    }
}