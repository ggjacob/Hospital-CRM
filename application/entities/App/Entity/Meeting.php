<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="meetings")
 */
class Meeting extends \App\Entity
{
    /**
     * @ManyToOne(targetEntity="Doctor", inversedBy="meetings", fetch="LAZY")
     */
    protected $doctor;
    
    /**
     * @ManyToOne(targetEntity="Patient", inversedBy="meetings", fetch="LAZY")
     */
    protected $patient;
    
    /**
     * @Column(type="datetime", nullable=false)
     */
    protected $reservationDateTime;
    
    /**
     * @Column(type="meetingStatus", nullable=false)
     */
    protected $status;
    
    public static function reserve($data) {
        $doctrineContainer = \Zend_Registry::get('doctrine');
        
        // fetch the entity manager.
        $em = $doctrineContainer->getEntityManager();
        
        $meeting = new Meeting();
        $meeting->doctor = $em->getRepository('App\Entity\Doctor')
                              ->find($data['doctorId']);
        
        $meeting->patient = $em->getRepository('App\Entity\Patient')
                               ->find($data['patientId']);
        $now = new \DateTime('now');
        $meeting->reservationDateTime = new \DateTime($now->format('Y-m-d') . ' ' . $data['hour'] . ':00');
        $meeting->status = \App\Entity\Meeting\Status::RESERVED;
        
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($meeting);
            
            $em->flush();
            
            $em->getConnection()->commit();
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }
    }
}