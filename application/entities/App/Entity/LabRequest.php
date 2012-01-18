<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="lab_requests")
 */
class LabRequest extends \App\Entity
{
    /**
     * @ManyToOne(targetEntity="Doctor", inversedBy="labRequests", fetch="LAZY")
     */
    protected $doctor;
    
    /**
     * @ManyToOne(targetEntity="Patient", inversedBy="labRequests", fetch="LAZY")
     */
    protected $patient;
    
    /**
     * @Column(type="string", length=1000, nullable=false)
     */
    protected $details;
    
    /**
     * @Column(type="string", length=1000, nullable=true)
     */
    protected $results;
    
    /**
     * @Column(type="labRequestStatus", nullable=false)
     */
    protected $status;
    
    public static function request($data) {
        $doctrineContainer = \Zend_Registry::get('doctrine');
        
        // fetch the entity manager.
        $em = $doctrineContainer->getEntityManager();
        
        $labRequest = new LabRequest();
        $labRequest->doctor = $em->getRepository('App\Entity\Doctor')
                              ->find($data['doctorId']);
        
        $labRequest->patient = $em->getRepository('App\Entity\Patient')
                               ->find($data['patientId']);
        $labRequest->details = $data['details'];
        $labRequest->status = \App\Entity\LabRequest\Status::WAITING;
        
        // log.
        $log = new \App\Entity\Log();
        $log->message = 'Lab request made for the patient.';
        $log->patient = $labRequest->patient;
        $log->createdAt = $log->updatedAt = new \DateTime('now');
        $labRequest->patient->logs->add($log);
        
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($labRequest);
            $em->persist($log);
            $em->flush();
            
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }
    }
    
    public static function response($data) {
        $doctrineContainer = \Zend_Registry::get('doctrine');
        
        // fetch the entity manager.
        $em = $doctrineContainer->getEntityManager();
        
        $labRequest = $em->getRepository('App\Entity\LabRequest')
                              ->find($data['labRequestId']);
        $labRequest->details = $data['details'];
        $labRequest->status = \App\Entity\LabRequest\Status::DELIVERED;
        
        // log.
        $log = new \App\Entity\Log();
        $log->message = 'Lab results for the patient are delivered.';
        $log->patient = $labRequest->patient;
        $log->createdAt = $log->updatedAt = new \DateTime('now');
        $patient->logs->add($log);
        
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($labRequest);
            $em->persist($log);
            $em->flush();
            
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }
    }
}