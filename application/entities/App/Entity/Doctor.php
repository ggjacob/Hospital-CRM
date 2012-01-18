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
        parent::__construct();
        
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getAvailableHours() {
        $this->_doctrineContainer = \Zend_Registry::get('doctrine');
        
        // fetch the entity manager.
        $em = $this->_doctrineContainer->getEntityManager(); 
        
        // current date.
        $curDateTime = new \DateTime('now');
        $curDate = $curDateTime->format('Y-m-d');
        
        // fetch today's meetings.
        $query = $em->createQuery('SELECT m FROM App\Entity\Meeting m WHERE m.reservationDateTime >= ?1 AND m.reservationDateTime <= ?2 AND m.status = ?3');
        $query->setParameter(1, $curDate . ' 00:00:00');
        $query->setParameter(2, $curDate . ' 23:59:59');
        $query->setParameter(3, \App\Entity\Meeting\Status::RESERVED);
        $meetings = $query->getResult();
        
        $all = array(
            '08:00', '08:30', '09:00', '09:30', '10:00',
            '10:30', '11:00', '11:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        );
        
        if (0 == count($meetings)) {
            return $all;
        }
        
        $periods = array(
            false, false, false, false, false, false, false, false,
            false, false, false, false, false, false, false, false
        );
        foreach ($meetings as $meeting) {
            $hour = $meeting->reservationDateTime->format('H');
            $min = $meeting->reservationDateTime->format('i');
            if ('08' == $hour) {
                if ('29' >= $min) {
                    $periods[0] = true;
                } else {
                    $periods[1] = true;
                }
            } else if ('09' == $hour) {
                if ('29' >= $min) {
                    $periods[2] = true;
                } else {
                    $periods[3] = true;
                }
            } else if ('10' == $hour) {
                if ('29' >= $min) {
                    $periods[4] = true;
                } else {
                    $periods[5] = true;
                }
            } else if ('11' == $hour) {
                if ('29' >= $min) {
                    $periods[6] = true;
                } else {
                    $periods[7] = true;
                }
            } else if ('13' == $hour) {
                if ('29' >= $min) {
                    $periods[8] = true;
                } else {
                    $periods[9] = true;
                }
            } else if ('14' == $hour) {
                if ('29' >= $min) {
                    $periods[10] = true;
                } else {
                    $periods[11] = true;
                }
            } else if ('15' == $hour) {
                if ('29' >= $min) {
                    $periods[12] = true;
                } else {
                    $periods[13] = true;
                }
            } else if ('16' == $hour) {
                if ('29' >= $min) {
                    $periods[14] = true;
                } else {
                    $periods[15] = true;
                }
            }
        }
        
        $returnArray = array();
        
        foreach ($all as $k => $v) {
            if (! $periods[$k] && $curDateTime->format('H:i') < $v) {
                $returnArray[] = $v;
            }
        }
        
        return $returnArray;
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