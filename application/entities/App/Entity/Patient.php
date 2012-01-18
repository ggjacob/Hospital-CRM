<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="patients")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="patientType")
 * @DiscriminatorMap({"INPATIENT" = "App\Entity\Patient\In", "OUTPATIENT" = "App\Entity\Patient\Out"})
 */
class Patient extends \App\Entity
{
    /**
     * @Column(type="string", length=50, nullable=false)
     */
    protected $name;
    
    /**
     * @Column(type="string", length=50, nullable=false)
     */
    protected $surname;
    
    /**
     * @Column(type="date", nullable=false)
     */
    protected $birthdate;
    
    /**
     * @Column(type="userGender", nullable=false)
     */
    protected $gender;
    
    /**
     * @Column(type="string", length=11, nullable=false)
     */
    protected $TCIdentityNumber;
    
    /**
     * @Column(type="string", length=1000, nullable=true)
     */
    protected $address;
    
    /**
     * @Column(type="string", length=20, nullable=true)
     */
    protected $contactNumber;
    
    /**
     * @ManyToMany(targetEntity="Inventory", mappedBy="patients", fetch="LAZY")
     */
    protected $medicineRequisitions;
    
    /**
     * @OneToMany(targetEntity="Meeting", mappedBy="patient", fetch="LAZY")
     */
    protected $meetings;
    
    /**
     * @OneToMany(targetEntity="Log", mappedBy="patient", fetch="LAZY")
     */
    protected $logs;
    
    /**
     * @Column(type="float", nullable=false)
     */
    protected $paymentAmount;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $paymentIsMade;
    
    /**
     * @OneToMany(targetEntity="LabRequest", mappedBy="patient", fetch="LAZY")
     */
    protected $labRequests;
    
    public function __construct() {
        $this->medicineRequisitions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->labRequests = new \Doctrine\Common\Collections\ArrayCollection();
    }
}