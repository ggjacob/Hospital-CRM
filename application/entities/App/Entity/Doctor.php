<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="doctors")
 */
class Doctor extends \App\Entity
{
    /**
     * @OneToOne(targetEntity="User", fetch="LAZY")
     */
    protected $user;
    
    /**
     * @Column(type="string", length=50, nullable=false)
     */
    protected $area;
    
    /**
     * @OneToMany(targetEntity="Meeting", mappedBy="doctor", fetch="LAZY")
     */
    protected $meetings;
    
    public function __construct() {
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public static function register($data) {
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        $em->getConnection()->beginTransaction();
        
        // 5 is for doctor (role id).
        $user = \App\Entity\User::register($data, 5);
        $doctor = new \App\Entity\Doctor();
        
        // mandatory fields.
        $doctor->area = $data['area'];
        $doctor->user = $user;
        $doctor->createdAt = $doctor->updatedAt = new \DateTime('now');
        
        try {
            $em->persist($doctor);
            $em->persist($user);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $ex) {
            $em->getConnection()->rollback();
            \Zend_Registry::get('logger')->err('Could not register the doctor. Error: ' . $ex->getMessage());
            
            return false;
        }

        return true;
    }
    
    public static function update($data) {
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        $patient = $em->getRepository('App\Entity\Patient')
                      ->find($data['id']);

        if (empty($patient) || empty($patient->id)) {
            throw new \Exception('Patient not found!');
        }
        
        // mandatory fields.
        $patient->name = $data['name'];
        $patient->surname = $data['surname'];
        $patient->birthdate = new \DateTime($data['birthdate']);
        $patient->admissionDateTime = $patient->createdAt = $patient->updatedAt = new \DateTime("now");
        $patient->gender = $data['gender'];
        $patient->TCIdentityNumber = $data['TCIdentityNumber'];
        
        if (isset($data['address'])) {
            $patient->address = $data['address'];
        }
        
        if (isset($data['contactNumber'])) {
            $patient->contactNumber = $data['contactNumber'];
        }
        
        $patient->paymentAmount = $data['paymentAmount'];
        if (isset($data['paymentIsMade'])) {
            if (1 == $data['paymentIsMade']) {
                $patient->paymentIsMade = true;
            } else {
                $patient->paymentIsMade = false;
            }
        }
        
        $patient->roomNumber = $data['roomNumber'];
        
        if (isset($data['operationIsRequired'])) {
            if (1 == $data['operationIsRequired']) {
                $patient->operationIsRequired = true;
            } else {
                $patient->operationIsRequired = false;
            }
        }
        
        $patient->status = \App\Entity\Patient\In\Status::UNDERTRAITMENT;
        
        // log.
        $log = new \App\Entity\Log();
        $log->message = 'Patient data is updated.';
        $log->patient = $patient;
        $log->createdAt = $log->updatedAt = new \DateTime('now');
        $patient->logs->add($log);
        
        try {
            $em->persist($patient);
            $em->persist($log);
            $em->flush();
        } catch (\Exception $ex) {
            \Zend_Registry::get('logger')->err('Could not register the patient. Error: ' . $ex->getMessage());
            
            return false;
        }

        return true;
    }
}