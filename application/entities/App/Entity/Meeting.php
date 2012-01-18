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
}