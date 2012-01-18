<?php

namespace App\Entity\Patient;

/**
 * @Entity
 * @Table(name="patients_out")
 */
class Out extends \App\Entity\Patient
{
    /**
     * @Column(type="text", length=1000, nullable=false)
     */
    protected $complaints;
    
    /**
     * @Column(type="text", length=1000, nullable=true)
     */
    protected $history;
    
    /**
     * @Column(type="text", length=1000, nullable=true)
     */
    protected $diagnosis;
    
    /**
     * @Column(type="text", length=1000, nullable=true)
     */
    protected $advise;
    
    public static function register($data) {
        $patient = new Out();
        
        // mandatory fields.
        $patient->name = $data['name'];
        $patient->surname = $data['surname'];
        $patient->birthdate = new \DateTime($data['birthdate']);
        $patient->createdAt = $patient->updatedAt = new \DateTime("now");
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
        
        $patient->complaints = $data['complaints'];
        
        if (isset($data['history'])) {
            $patient->history = $data['history'];
        }
        
        if (isset($data['diagnosis'])) {
            $patient->diagnosis = $data['diagnosis'];
        }
        
        if (isset($data['advise'])) {
            $patient->advise = $data['advise'];
        }
        
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        try {
            $em->persist($patient);
            $em->flush();
        } catch (\Exception $ex) {
            \Zend_Registry::get('logger')->err('Could not register the patient. Error: ' . $ex->getMessage());
            
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
        $patient->createdAt = $patient->updatedAt = new \DateTime("now");
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
        
        $patient->complaints = $data['complaints'];
        
        if (isset($data['history'])) {
            $patient->history = $data['history'];
        }
        
        if (isset($data['diagnosis'])) {
            $patient->diagnosis = $data['diagnosis'];
        }
        
        if (isset($data['advise'])) {
            $patient->advise = $data['advise'];
        }
        
        try {
            $em->persist($patient);
            $em->flush();
        } catch (\Exception $ex) {
            \Zend_Registry::get('logger')->err('Could not register the patient. Error: ' . $ex->getMessage());
            
            return false;
        }

        return true;
    }
}