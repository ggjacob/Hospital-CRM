<?php

namespace App\Entity\Patient;

/**
 * @Entity
 * @Table(name="patients_in")
 */
class In extends \App\Entity\Patient
{
    /**
     * @Column(type="integer", nullable=false)
     */
    protected $roomNumber;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $operationIsRequired;
    
    /**
     * @Column(type="inpatientStatus", nullable=false)
     */
    protected $status;
    
    /**
     * @Column(type="datetime", nullable=false)
     */
    protected $admissionDateTime;
    
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $dischargeDateTime;
    
    /**
     * @Column(type="text", size=1000, nullable=true)
     */
    protected $dischargeNotes;
}