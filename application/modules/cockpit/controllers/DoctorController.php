<?php

class Cockpit_DoctorController extends ZFS_Cockpit_Controller
{
    public function registerAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            if ('register_patient' == $request->getPost('operation')) {
                $form = new ZFS_Form_Cockpit_Doctor(
                    $request->getPost()
                );
                
                if ($form->isValid($request->getPost())) {
                    if (ZFS_Form_Cockpit_Doctor::save($request->getPost()))
                    {
                        $this->_redirect('/cockpit/doctor/list');
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
            $form = new ZFS_Form_Cockpit_Doctor();
        }
        
        // set the form for the view.
        $this->view->form = $form;
    }
    
    public function detailsAction()
    {
        // local variable.
        $request = $this->getRequest();
        
        // if the current request is of type "POST";
        if ($request->isPost()) {
            // if the current operation is "news_edit";
            if ('register_patient' == $request->getPost('operation')) {
                // create the form using the post data.
                $form = new ZFS_Form_Cockpit_Doctor(
                    $request->getPost()
                );
                
                // if the news data is ok;
                if ($form->isValid($request->getPost())) {
                    // they can be stored;
                    if (ZFS_Form_Cockpit_Doctor::update($request->getPost()))
                    {
                        // redirect the user to the news listing page.
                        $this->_redirect('/cockpit/doctor/list');
                    }
                    else
                    {
                        // set the error message.
                        $this->view->errorMessages['register_patient'] = array(
                            'message' => 'Patient data could not be updated!'
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
            $patientId = (int) $request->getParam('patientId');
            
            $patient = $this->_doctrineContainer->getEntityManager()
                         ->getRepository('App\Entity\Doctor')
                         ->find($patientId);
            
            $form = new ZFS_Form_Cockpit_Doctor($patient);
        }
        
        // set the form for the view.
        $this->view->form = $form;
    }
    
    public function listAction()
    {
        // fetch the entity manager instance.
        $em = $this->_doctrineContainer->getEntityManager();
        
        // create the grid object.
        $grid = new XGrid();
        
        $userField = new XGrid_DataField_Text();
        $grid->addField('user', $this->translate->_('Doctor'), 
            $userField->addKey('user')->addKey('name')
        );
        $grid->addField('area', 'Area', XGrid_DataField::TEXT);
        $editOperation = new XGrid_DataField_Url();
        $editOperation->registerOnRender(function(XGrid_DataField_Event $event) {
            $data = $event->getData();
            $event->getDataField()->setDisplayText('Edit');
            $event->getDataField()->setAttributes(array(
                'class' => 'edit',
                'title' => 'Edit doctor data'
            ));
            return '' . $data->id;
        });
        $grid->addField('edit_operation', '', $editOperation);
        
        // set pagination.
        $paginator = new XGrid_Plugin_DefaultPaginator();
        $paginator->setCurrentPage($this->_getPage());
        $paginator->setItemCountPerPage(20);
        $paginator->setRange(10);
        $paginator->setType(XGrid_Plugin_Pagination::SLIDING);
        
        $grid->registerPlugin($paginator);

        // set the data source for the grid.
        $grid->setDataSource(
            new XGrid_DataSource_Doctrine2(
                $em->createQuery('SELECT d FROM App\Entity\Doctor d ORDER BY d.id DESC'),
                $em->createQuery('SELECT COUNT(d) FROM App\Entity\Doctor d ORDER BY d.id DESC')
            )
        );
        
        // add the corresponding classes to the table helper.
        $grid->getHtmlHelper()->addAttribute('class', 'width_full noFloat clear center');
        
        // set the view instance for the grid.
        $this->view->grid = $grid;
    }
    
    public function scheduleAction() {
        // fetch the entity manager.
        $em = $this->_doctrineContainer->getEntityManager();
        
        // find corresponding doctors.
        $doctor = $em->getRepository('App\Entity\User')
                     ->find(Zend_Auth::getInstance()->getIdentity()->id)
                     ->doctor;

        $this->view->doctor = $doctor;
    }
}